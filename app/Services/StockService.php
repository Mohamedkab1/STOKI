<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Enregistrer un mouvement de stock
     * 
     * @param Product $product
     * @param string $type ('in', 'out', 'adjustment')
     * @param int $quantity
     * @param string|null $note
     * @param string|null $date
     * @return StockMovement
     * @throws \Exception
     */
    public function recordMovement(Product $product, string $type, int $quantity, ?string $note = null, ?string $date = null)
    {
        return DB::transaction(function () use ($product, $type, $quantity, $note, $date) {
            // Validation de la quantité
            if ($quantity <= 0) {
                throw new \Exception("La quantité doit être supérieure à 0.");
            }

            // ✅ BUG FIX: Lire le stock réel depuis la base de données avec un verrou
            // pour éviter les lectures «hors-ligne» (stale model) d'Eloquent.
            // Sans ça, $product->quantity peut être 0 même si le stock est à jour en base.
            $freshProduct = Product::lockForUpdate()->find($product->id);

            if (!$freshProduct) {
                throw new \Exception("Produit introuvable (ID: {$product->id}).");
            }

            $stockAvant = $freshProduct->quantity;

            \Log::info("[StockService] Début opération", [
                'product_id'   => $freshProduct->id,
                'product_name' => $freshProduct->name,
                'type'         => $type,
                'quantite'     => $quantity,
                'stock_avant'  => $stockAvant,
            ]);

            // Calcul de l'impact sur le stock
            $stockChange = 0;

            if ($type === StockMovement::TYPE_IN) {
                $stockChange = $quantity;

            } elseif ($type === StockMovement::TYPE_OUT) {
                // Vérification du stock sur la valeur fraîche (pas le modèle reçu en paramètre)
                if ($freshProduct->quantity < $quantity) {
                    throw new \Exception(
                        "Stock insuffisant pour le produit \u00ab {$freshProduct->name} \u00bb. "
                        . "Disponible : {$freshProduct->quantity}, Demandé : {$quantity}"
                    );
                }
                $stockChange = -$quantity;

            } elseif ($type === StockMovement::TYPE_ADJUSTMENT) {
                // L'ajustement est toujours additif (le controlleur passe un écart signé)
                $stockChange = $quantity;
            } else {
                throw new \Exception("Type de mouvement invalide : {$type}.");
            }

            // Créer le mouvement
            $movement = StockMovement::create([
                'product_id' => $freshProduct->id,
                'type'       => $type,
                'quantity'   => $quantity,
                'note'       => $note,
                'reason'     => $note,
                'created_at' => $date ? \Carbon\Carbon::parse($date) : now(),
                'updated_at' => now(),
            ]);

            // Mettre à jour le stock (sur l'entité fraîche verrouillée)
            $freshProduct->increment('quantity', $stockChange);

            $stockApres = $freshProduct->fresh()->quantity;

            \Log::info("[StockService] Opération terminée", [
                'product_id'  => $freshProduct->id,
                'type'        => $type,
                'quantite'    => $quantity,
                'stock_avant' => $stockAvant,
                'stock_apres' => $stockApres,
                'mouvement_id'=> $movement->id,
            ]);

            // Synchroniser l'instance originale pour le code en aval
            $product->quantity = $stockApres;

            return $movement;
        });
    }

    /**
     * Recalculer le stock théorique à partir de l'historique
     */
    public function syncStock(Product $product)
    {
        return $product->getCurrentStock();
    }
}
