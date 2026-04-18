<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Models\Invoice;
use App\Models\ProductBatch;
use App\Services\FIFOService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\StockMovementNotification;
use App\Notifications\InvoiceGeneratedNotification;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    protected $fifoService;
    protected $stockService;

    public function __construct(FIFOService $fifoService, \App\Services\StockService $stockService)
    {
        $this->fifoService = $fifoService;
        $this->stockService = $stockService;
    }

    /**
     * Afficher la liste des produits
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('stock') && $request->stock == 'low') {
            $query->whereRaw('quantity <= min_stock');
        }

        $products = $query->orderBy('name')->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Enregistrer un produit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $initialQuantity = $validated['quantity'];
        $validated['quantity'] = 0;

        $product = Product::create($validated);

        // Si quantité initiale > 0, créer un lot initial via le service (qui incrémentera le stock une seule fois)
        if ($initialQuantity > 0) {
            $this->fifoService->recordPurchase($product, $initialQuantity, $product->price, [
                'reason' => 'Stock initial',
                'supplier' => 'Stock Initial'
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produit créé avec succès.');
    }

    /**
     * Afficher les détails
     */
    public function show(Product $product)
    {
        $movements = $product->stockMovements()
            ->with(['invoice'])
            ->latest()
            ->paginate(10);
        
        return view('products.show', compact('product', 'movements'));
    }

    /**
     * Éditer un produit
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour.');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Product $product)
    {
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produit supprimé.');
    }

    public function adjustStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type'              => 'required|in:in,out,adjustment',
            'quantity'          => 'required|integer|min:1',
            'unit_price'        => 'nullable|numeric|min:0',
            'selling_price'     => 'nullable|numeric|min:0',
            'reason'            => 'nullable|string',
            'customer_supplier' => 'nullable|string|max:255',
            'payment_method'    => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Définir le prix unitaire selon le type d'opération
            $priceUsed = match($validated['type']) {
                'in'         => floatval($validated['unit_price'] ?? $product->purchase_price ?? 0),
                'out'        => floatval($validated['selling_price'] ?? $product->price ?? 0),
                'adjustment' => floatval($validated['unit_price'] ?? $product->purchase_price ?? 0),
                default      => 0
            };

            $invoiceType = $validated['type'] === 'in' ? 'purchase' : 'sale';
            $note = $validated['reason'] ?? match($validated['type']) {
                'in'         => 'Achat',
                'out'        => 'Vente',
                'adjustment' => 'Ajustement de stock',
                default      => 'Mouvement'
            };

            // 1. Enregistrer le mouvement via le StockService (gère l'update du stock)
            $movement = $this->stockService->recordMovement(
                $product,
                $validated['type'],
                $validated['quantity'],
                $note
            );

            $calculatedTotal = $validated['quantity'] * $priceUsed;

            // 2. Créer la facture (uniquement pour achat/vente, pas pour l'ajustement)
            $invoice = null;
            if (in_array($validated['type'], ['in', 'out'])) {
                $invoiceNumber = Invoice::generateInvoiceNumber($invoiceType);

                $invoice = Invoice::create([
                    'invoice_number'    => $invoiceNumber,
                    'type'              => $invoiceType,
                    'movement_type'     => $validated['type'],
                    'product_id'        => $product->id,
                    'quantity'          => $validated['quantity'],
                    'unit_price'        => $priceUsed,
                    'total_amount'      => $calculatedTotal,
                    'reason'            => $note,
                    'customer_supplier' => $validated['customer_supplier'] ?? 'N/A',
                    'payment_method'    => $validated['payment_method'] ?? 'Espèces',
                    'payment_status'    => 'paid',
                    'invoice_date'      => now()
                ]);

                // 3. Lier la facture au mouvement
                $movement->update([
                    'invoice_id'  => $invoice->id,
                    'unit_price'  => $priceUsed,
                    'total_price' => $calculatedTotal
                ]);
            }

            DB::commit();

            $stockActuel = $product->fresh()->quantity;
            $successMsg  = "Opération enregistrée avec succès ! Stock actuel : {$stockActuel} unités.";

            if ($invoice) {
                return redirect()->route('invoices.show', $invoice)->with('success', $successMsg);
            }

            return redirect()->route('products.show', $product)->with('success', $successMsg);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur adjustStock: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'request'    => $request->all()
            ]);
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function recalculateStock(Product $product)
    {
        $product->getCurrentStock();
        return response()->json(['success' => true, 'stock' => $product->quantity]);
    }

    public function fifoHistory(Product $product)
    {
        // ... gardé si nécessaire ou supprimé car inclus dans show
        return view('products.fifo-history', compact('product'));
    }
}