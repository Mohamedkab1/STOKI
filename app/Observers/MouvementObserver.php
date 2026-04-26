<?php
// app/Observers/MouvementObserver.php

namespace App\Observers;

use App\Models\StockMovement;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * Observer pour le modèle StockMovement (mouvements de stock)
 *
 * À chaque création d'un mouvement, cet observer :
 * 1. Génère une notification basée sur le type de mouvement
 * 2. Vérifie le niveau de stock du produit concerné
 * 3. Crée une notification d'alerte si le stock est faible ou épuisé
 */
class MouvementObserver
{
    /**
     * Déclenché après la création d'un mouvement de stock
     *
     * @param StockMovement $mouvement Le mouvement créé
     */
    public function created(StockMovement $mouvement): void
    {
        // Charger le produit associé
        $mouvement->loadMissing('product');
        $produit = $mouvement->product;

        if (!$produit) {
            return;
        }

        // Déterminer l'utilisateur (celui connecté ou celui du mouvement si disponible)
        $userId = Auth::id() ?? ($mouvement->user_id ?? 1); // fallback ID 1 pour commandes background

        if (!$userId) {
            return;
        }

        // ========== 1. Notification du mouvement ==========
        $notifData = $this->getNotificationData($mouvement, $produit);

        Notification::create([
            'user_id'  => $userId,
            'title'    => $notifData['title'],
            'body'     => $notifData['body'],
            'type'     => $notifData['type'],
            'category' => $notifData['category'],
            'is_read'  => false,
        ]);

        // ========== 2. Vérification du niveau de stock ==========
        // Rafraîchir le produit pour avoir le stock à jour
        $produit->refresh();

        $stockActuel  = $produit->quantity;
        $stockMinimum = $produit->min_stock;

        // Si le stock est faible ou épuisé, on notifie TOUS les admins (y compris Super Admin)
        if ($stockActuel <= $stockMinimum || $stockActuel <= 0) {
            $admins = \App\Models\User::where('status', 'active')
                ->whereIn('role', ['admin', 'superadmin'])
                ->get();

            foreach ($admins as $admin) {
                if ($stockActuel <= 0) {
                    Notification::create([
                        'user_id'  => $admin->id,
                        'title'    => 'Rupture de Stock : ' . $produit->name,
                        'body'     => 'Le produit est épuisé ! Action requise.',
                        'type'     => 'danger',
                        'category' => 'alerte_stock',
                    ]);
                } elseif ($stockMinimum > 0 && $stockActuel <= $stockMinimum) {
                    Notification::create([
                        'user_id'  => $admin->id,
                        'title'    => 'Stock Faible : ' . $produit->name,
                        'body'     => "Niveau critique : {$stockActuel} / {$stockMinimum} unités.",
                        'type'     => 'warning',
                        'category' => 'alerte_stock',
                    ]);
                }
            }
        }
    }

    /**
     * Construire le titre, le corps, le type et la categorie de notification
     * selon le type de mouvement
     *
     * @param StockMovement $mouvement
     * @param mixed $produit
     * @return array
     */
    private function getNotificationData(StockMovement $mouvement, $produit): array
    {
        $nomProduit = $produit->name;
        $quantite   = $mouvement->quantity;
        $type       = strtolower($mouvement->type);

        if (in_array($type, ['in', 'entrée', 'entree'])) {
            return [
                'title'    => 'Entrée stock — ' . $nomProduit,
                'body'     => '+' . $quantite . ' unités reçues',
                'type'     => 'success',
                'category' => 'entree',
            ];
        }

        if (in_array($type, ['out', 'sortie'])) {
            return [
                'title'    => 'Sortie stock — ' . $nomProduit,
                'body'     => '-' . $quantite . ' unités sorties',
                'type'     => 'info',
                'category' => 'sortie',
            ];
        }

        if (in_array($type, ['transfert', 'transfer'])) {
            return [
                'title'    => 'Transfert — ' . $nomProduit,
                'body'     => $quantite . ' unités transférées',
                'type'     => 'info',
                'category' => 'sortie',
            ];
        }

        if (in_array($type, ['adjustment', 'ajustement'])) {
            return [
                'title'    => 'Ajustement — ' . $nomProduit,
                'body'     => 'Stock ajusté à ' . $quantite . ' unités',
                'type'     => 'warning',
                'category' => 'sortie',
            ];
        }

        // Par défaut
        return [
            'title'    => 'Mouvement — ' . $nomProduit,
            'body'     => 'Mouvement de ' . $quantite . ' unités',
            'type'     => 'info',
            'category' => 'sortie',
        ];
    }
}
