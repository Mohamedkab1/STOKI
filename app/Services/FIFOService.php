<?php
// app/Services/FIFOService.php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FIFOService
{
    /**
     * Enregistrer une entrée de stock (achat)
     */
    public function recordPurchase(Product $product, int $quantity, float $purchasePrice, array $data = []): ProductBatch
    {
        return DB::transaction(function () use ($product, $quantity, $purchasePrice, $data) {
            // Créer un nouveau lot
            $batch = ProductBatch::create([
                'product_id' => $product->id,
                'batch_number' => ProductBatch::generateBatchNumber($product->id),
                'purchase_price' => $purchasePrice,
                'initial_quantity' => $quantity,
                'remaining_quantity' => $quantity,
                'received_date' => $data['received_date'] ?? now(),
                'expiry_date' => $data['expiry_date'] ?? null,
                'manufacturing_date' => $data['manufacturing_date'] ?? null,
                'supplier' => $data['supplier'] ?? null,
                'notes' => $data['notes'] ?? null
            ]);

            // Enregistrer le mouvement de stock
            StockMovement::create([
                'product_id' => $product->id,
                'product_batch_id' => $batch->id,
                'type' => 'in',
                'quantity' => $quantity,
                'unit_price' => $purchasePrice,
                'total_price' => $quantity * $purchasePrice,
                'reason' => $data['reason'] ?? 'Achat',
                'reference_type' => 'purchase',
                'reference_id' => $data['invoice_id'] ?? null
            ]);

            // Mettre à jour le stock total du produit
            $product->increment('quantity', $quantity);

            return $batch;
        });
    }

    /**
     * Enregistrer une sortie de stock (vente) avec logique FIFO
     */
    public function recordSale(Product $product, int $quantity, array $data = []): array
    {
        return DB::transaction(function () use ($product, $quantity, $data) {
            // Récupérer tous les lots valides pour ce produit (FIFO: plus ancien d'abord)
            $availableBatches = ProductBatch::where('product_id', $product->id)
                ->where('remaining_quantity', '>', 0)
                ->where('is_active', true)
                ->where(function ($query) {
                    // Exclure les lots expirés
                    $query->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>', now());
                })
                ->orderBy('received_date', 'asc')  // FIFO: plus ancien d'abord
                ->orderBy('expiry_date', 'asc')    // Puis par date d'expiration
                ->orderBy('id', 'asc')
                ->get();

            // Vérifier le stock total disponible
            $totalAvailable = $availableBatches->sum('remaining_quantity');
            if ($totalAvailable < $quantity) {
                throw new \Exception("Stock insuffisant. Disponible: {$totalAvailable}, Demandé: {$quantity}");
            }

            $remainingToSell = $quantity;
            $totalCost = 0;
            $usedBatches = [];
            $movements = [];

            // Parcourir les lots du plus ancien au plus récent
            foreach ($availableBatches as $batch) {
                if ($remainingToSell <= 0) break;

                $quantityFromThisBatch = min($batch->remaining_quantity, $remainingToSell);
                
                // Calculer le coût pour cette partie
                $costForThisBatch = $quantityFromThisBatch * $batch->purchase_price;
                $totalCost += $costForThisBatch;

                // Réduire la quantité restante dans le lot
                $batch->decrement('remaining_quantity', $quantityFromThisBatch);
                
                // Désactiver le lot si quantité épuisée
                if ($batch->remaining_quantity <= 0) {
                    $batch->update(['is_active' => false]);
                }

                // Enregistrer le mouvement pour ce lot
                $movement = StockMovement::create([
                    'product_id' => $product->id,
                    'product_batch_id' => $batch->id,
                    'type' => 'out',
                    'quantity' => $quantityFromThisBatch,
                    'unit_price' => $batch->purchase_price, // Prix d'achat pour calcul du coût
                    'total_price' => $costForThisBatch,
                    'reason' => $data['reason'] ?? 'Vente',
                    'reference_type' => 'sale',
                    'reference_id' => $data['invoice_id'] ?? null
                ]);

                $movements[] = $movement;
                $usedBatches[] = [
                    'batch_id' => $batch->id,
                    'batch_number' => $batch->batch_number,
                    'quantity' => $quantityFromThisBatch,
                    'unit_price' => $batch->purchase_price,
                    'subtotal' => $costForThisBatch
                ];

                $remainingToSell -= $quantityFromThisBatch;
            }

            // Mettre à jour le stock total du produit
            $product->decrement('quantity', $quantity);

            // Calculer le prix de vente moyen (si fourni)
            $sellingPrice = $data['selling_price'] ?? null;
            $totalRevenue = $sellingPrice ? $quantity * $sellingPrice : 0;
            $profit = $sellingPrice ? $totalRevenue - $totalCost : 0;

            return [
                'success' => true,
                'total_quantity' => $quantity,
                'total_cost' => $totalCost,
                'average_cost' => $totalCost / $quantity,
                'selling_price' => $sellingPrice,
                'total_revenue' => $totalRevenue,
                'profit' => $profit,
                'used_batches' => $usedBatches,
                'movements' => $movements
            ];
        });
    }

    /**
     * Obtenir la valorisation du stock par lot (FIFO)
     */
    public function getStockValuation(Product $product): array
    {
        $activeBatches = ProductBatch::where('product_id', $product->id)
            ->where('remaining_quantity', '>', 0)
            ->where('is_active', true)
            ->orderBy('received_date', 'asc')
            ->get();

        $valuation = [
            'product' => $product->name,
            'total_quantity' => $product->quantity,
            'total_value' => 0,
            'batches' => []
        ];

        foreach ($activeBatches as $batch) {
            $batchValue = $batch->remaining_quantity * $batch->purchase_price;
            $valuation['total_value'] += $batchValue;
            
            $valuation['batches'][] = [
                'batch_number' => $batch->batch_number,
                'received_date' => $batch->received_date->format('d/m/Y'),
                'expiry_date' => $batch->expiry_date?->format('d/m/Y'),
                'quantity' => $batch->remaining_quantity,
                'unit_price' => $batch->purchase_price,
                'total_value' => $batchValue,
                'supplier' => $batch->supplier,
                'is_expired' => $batch->isExpired()
            ];
        }

        return $valuation;
    }

    /**
     * Obtenir les lots proches de l'expiration
     */
    public function getExpiringBatches(int $daysThreshold = 30)
    {
        $threshold = now()->addDays($daysThreshold);
        
        return ProductBatch::with('product')
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $threshold)
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date', 'asc')
            ->get();
    }

    /**
     * Obtenir les lots expirés
     */
    public function getExpiredBatches()
    {
        return ProductBatch::with('product')
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->get();
    }
}