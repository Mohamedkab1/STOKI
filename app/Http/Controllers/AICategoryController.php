<?php
// app/Http/Controllers/AICategoryController.php

namespace App\Http\Controllers;

use App\Services\AICategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AICategoryController extends Controller
{
    public function __construct(
        protected AICategoryService $aiService
    ) {}

    /**
     * AJAX — Suggère une catégorie pour un produit via Gemini IA.
     *
     * POST /ai/suggest-category
     * Body: { name: string, description: string }
     * Returns: { success, category_id, category_name, confidence, reason }
     */
    public function suggestCategory(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|min:2|max:255',
                'description' => 'nullable|string|max:1000',
            ]);

            $result = $this->aiService->suggestCategory(
                productName : $validated['name'],
                description : $validated['description'] ?? '',
                userId      : auth()->id(),
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 422);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('AI Suggestion Error: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur interne du serveur: ' . $e->getMessage(),
            ], 500);
        }
    }
}
