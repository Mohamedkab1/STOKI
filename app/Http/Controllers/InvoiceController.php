<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\StockMovement;
use App\Notifications\InvoiceGeneratedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('product');
        
        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }
        
        $invoices = $query->latest()->paginate(15);

        // Statistiques pour les KPIs
        $stats = [
            'total_sales' => Invoice::where('type', 'sale')->sum('total_amount'),
            'total_purchases' => Invoice::where('type', 'purchase')->sum('total_amount'),
            'pending_amount' => Invoice::where('payment_status', 'pending')->sum('total_amount'),
            'count' => Invoice::count()
        ];
        
        return view('invoices.index', compact('invoices', 'stats'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('product', 'items.product');
        return view('invoices.show', compact('invoice'));
    }

    public function create()
    {
        $products = Product::all();
        return view('invoices.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:purchase,sale',
            'movement_type' => 'required|in:in,out',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'customer_supplier' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string',
            'reason' => 'nullable|string'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        // Vérifier le stock pour une vente
        if ($validated['movement_type'] === 'out' && $product->quantity < $validated['quantity']) {
            return back()->with('error', 'Stock insuffisant pour cette vente.');
        }

        // Générer le numéro de facture
        $invoiceNumber = Invoice::generateInvoiceNumber($validated['type']);
        
        // Calculer le montant total
        $totalAmount = $validated['quantity'] * $validated['unit_price'];

        // Créer la facture
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'type' => $validated['type'],
            'movement_type' => $validated['movement_type'],
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'total_amount' => $totalAmount,
            'reason' => $validated['reason'],
            'customer_supplier' => $validated['customer_supplier'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid', // ou 'pending' selon votre logique
            'invoice_date' => now()
        ]);
        // NOTIFICATION: Facture générée
        Notification::send(auth()->user(), new InvoiceGeneratedNotification($invoice));

        // Créer le mouvement de stock associé
        StockMovement::create([
            'product_id' => $validated['product_id'],
            'type' => $validated['movement_type'],
            'quantity' => $validated['quantity'],
            'reason' => $validated['reason'] ?? ($validated['type'] === 'purchase' ? 'Achat' : 'Vente'),
            'price' => $validated['unit_price']
        ]);

        // Mettre à jour le stock
        $product->updateStock($validated['quantity'], $validated['movement_type']);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès.');

        
    }

    public function generatePDF(Invoice $invoice)
    {
        $invoice->load('product', 'items.product');
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        return $pdf->download('facture-' . $invoice->invoice_number . '.pdf');
    }

    public function updatePaymentStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:paid,pending,cancelled'
        ]);

        $invoice->update(['payment_status' => $validated['payment_status']]);

        return back()->with('success', 'Statut de paiement mis à jour.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Facture supprimée avec succès.');
    }
}