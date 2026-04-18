<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StockMovementApiController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Liste des mouvements avec filtres
     */
    public function index(Request $request): JsonResponse
    {
        $query = StockMovement::with('product');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }

        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        $movements = $query->latest()->paginate($request->get('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data' => $movements
        ]);
    }

    /**
     * Enregistrer un nouveau mouvement
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        try {
            $product = Product::findOrFail($validated['product_id']);
            
            // Pour l'ajustement, on permet de passer un multiplicateur ou une logique de signe
            // Ici on simplifie : in/out sont positifs, adjustment peut être signé ou on utilise un champ 'direction'
            // On va suivre la logique : out = soustraction, in = addition, adjustment = addition (si négatif, soustrait)
            
            $movement = $this->stockService->recordMovement(
                $product,
                $validated['type'],
                $validated['quantity'],
                $validated['note'],
                $validated['date'] ?? null
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Mouvement enregistré avec succès',
                'data' => $movement
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
