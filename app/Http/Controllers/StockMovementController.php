<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Afficher l'historique des mouvements
     */
    public function index(Request $request)
    {
        $query = StockMovement::with('product');

        // Filtres
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $movements = $query->latest()->paginate(15);
        $products = Product::orderBy('name')->get();

        return view('stock_movements.index', compact('movements', 'products'));
    }

    /**
     * Enregistrer un nouveau mouvement (depuis la page globale)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        try {
            $product = Product::findOrFail($validated['product_id']);
            
            $this->stockService->recordMovement(
                $product,
                $validated['type'],
                $validated['quantity'],
                $validated['note']
            );

            return redirect()->route('stock-movements.index')
                ->with('success', 'Mouvement de stock enregistré avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
