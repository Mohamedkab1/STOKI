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
use Illuminate\Support\Facades\Notification;  // ← AJOUTEZ CECI
use App\Notifications\LowStockNotification;    // ← AJOUTEZ CECI
use App\Notifications\StockMovementNotification; // ← AJOUTEZ CECI
use App\Notifications\InvoiceGeneratedNotification; // ← AJOUTEZ CECI
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $fifoService;

    public function __construct(FIFOService $fifoService)
    {
        $this->fifoService = $fifoService;
    }

    /**
     * Afficher la liste des produits
     */
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->paginate(10);
        
        return view('products.index', compact('products'));
    }

    /**
     * Afficher le formulaire de création d'un produit
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validation de l'image

        ]);
            // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Afficher les détails d'un produit
     */
    public function show(Product $product)
    {
        $movements = $product->stockMovements()
            ->with(['batch', 'invoice'])
            ->latest()
            ->paginate(10);
        
        return view('products.show', compact('product', 'movements'));
    }

    /**
     * Afficher le formulaire d'édition d'un produit
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
            'min_stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validation de l'image
        ]);

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Ajuster le stock avec FIFO
     */
    public function adjustStock(Request $request, Product $product)
    {
        try {
            // Validation
            $validated = $request->validate([
                'type' => 'required|in:in,out',
                'quantity' => 'required|integer|min:1',
                'reason' => 'nullable|string',
                'customer_supplier' => 'required|string|max:255',
                'payment_method' => 'required|string'
            ]);

            // Vérifier le stock pour une sortie
            if ($validated['type'] === 'out' && $product->quantity < $validated['quantity']) {
                return back()->with('error', 'Stock insuffisant. Disponible: ' . $product->quantity);
            }

            DB::beginTransaction();

            // 1. Créer le mouvement de stock
            $movement = StockMovement::create([
                'product_id' => $product->id,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'unit_price' => $product->price,
                'total_price' => $validated['quantity'] * $product->price,
                'reason' => $validated['reason'] ?? null
            ]);

            // 2. Mettre à jour le stock
            if ($validated['type'] === 'in') {
                $product->increment('quantity', $validated['quantity']);
                $invoiceType = 'purchase';
            } else {
                $product->decrement('quantity', $validated['quantity']);
                $invoiceType = 'sale';
            }

            // 3. Créer la facture
            $invoice = Invoice::create([
                'invoice_number' => 'FACT-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'type' => $invoiceType,
                'movement_type' => $validated['type'],
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $product->price,
                'total_amount' => $validated['quantity'] * $product->price,
                'reason' => $validated['reason'] ?? ($validated['type'] === 'in' ? 'Achat' : 'Vente'),
                'customer_supplier' => $validated['customer_supplier'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'invoice_date' => now()
            ]);

            // 4. Lier la facture au mouvement
            $movement->update(['invoice_id' => $invoice->id]);

            DB::commit();

            // ========== NOTIFICATIONS UNIQUEMENT ==========
            $user = auth()->user();
            if ($user) {
                // Notification de mouvement de stock
                $user->notify(new StockMovementNotification($movement, $product));
                
                // Notification de facture générée
                $user->notify(new InvoiceGeneratedNotification($invoice));
                
                // Notification de stock faible (sans message flash)
                if ($product->isLowStock()) {
                    $user->notify(new LowStockNotification($product));
                    // PAS DE SESSION flash ici - juste la notification
                }
        }
        // =============================================

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Opération effectuée avec succès !');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur adjustStock: ' . $e->getMessage());
            return back()->with('error', 'Erreur: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Afficher l'historique FIFO d'un produit
     */
    public function fifoHistory(Product $product)
    {
        // Récupérer tous les mouvements FIFO avec les lots
        $movements = StockMovement::with(['batch', 'invoice'])
            ->where('product_id', $product->id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Calculer l'historique FIFO
        $fifoHistory = $this->calculateFIFOHistory($product, $movements);
        
        return view('products.fifo-history', compact('product', 'fifoHistory'));
    }

    /**
     * Calculer l'historique FIFO
     */
    private function calculateFIFOHistory($product, $movements)
    {
        $history = [];
        $batches = []; // Pour suivre les lots en stock
        
        foreach ($movements as $movement) {
            $entry = [
                'date' => $movement->created_at->format('d-m-Y'),
                'type' => $movement->type,
                'quantity' => $movement->quantity,
                'unit_price' => $movement->unit_price,
                'total_value' => $movement->total_price,
                'batch_number' => $movement->batch?->batch_number ?? '-',
                'stock_after' => null,
                'stock_value_after' => null,
                'details' => []
            ];
            
            if ($movement->type === 'in') {
                // Entrée de stock
                $entry['stock_after'] = $product->quantity; // À améliorer
                
                // Ajouter le lot au stock
                if ($movement->batch) {
                    $batches[] = [
                        'batch_id' => $movement->batch->id,
                        'batch_number' => $movement->batch->batch_number,
                        'quantity' => $movement->batch->remaining_quantity,
                        'unit_price' => $movement->batch->purchase_price,
                        'received_date' => $movement->batch->received_date
                    ];
                }
                
                // Calculer la valeur totale du stock après cette entrée
                $entry['stock_value_after'] = $this->calculateStockValue($batches);
                
            } else {
                // Sortie de stock - Détail FIFO
                $entry['details'] = $this->getSaleDetails($movement);
                $entry['stock_after'] = $product->quantity;
                $entry['stock_value_after'] = $this->calculateStockValue($batches);
            }
            
            $history[] = $entry;
        }
        
        return $history;
    }

    /**
     * Calculer la valeur totale du stock
     */
    private function calculateStockValue($batches)
    {
        $total = 0;
        foreach ($batches as $batch) {
            $total += ($batch['quantity'] ?? 0) * ($batch['unit_price'] ?? 0);
        }
        return $total;
    }

    /**
     * Obtenir les détails d'une vente
     */
    private function getSaleDetails($movement)
    {
        // Cette méthode devrait retourner les détails des lots utilisés pour cette vente
        // À implémenter selon votre structure de données
        return [];
    }

    /**
     * Afficher la valorisation du stock (FIFO)
     */
    public function valuation(Product $product)
    {
        $valuation = $this->fifoService->getStockValuation($product);
        return view('products.valuation', compact('valuation'));
    }

    /**
     * Afficher les lots d'un produit
     */
    public function batches(Product $product)
    {
        $batches = ProductBatch::where('product_id', $product->id)
            ->orderBy('received_date', 'desc')
            ->paginate(20);
        
        return view('products.batches', compact('product', 'batches'));
    }
}