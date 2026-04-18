<?php
// app/Observers/FactureObserver.php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Observer pour le modèle Invoice (factures)
 *
 * À la modification d'une facture, si son statut change, 
 * on génère une notification de catégorie 'facture'.
 */
class FactureObserver
{
    /**
     * Déclenché lors de la mise à jour d'une facture
     *
     * @param Invoice $invoice
     */
    public function updated(Invoice $invoice): void
    {
        // On vérifie si c'est bien le statut qui a été modifié
        if ($invoice->isDirty('payment_status')) {
            $nouveauStatut = strtolower($invoice->payment_status);
            
            // Définir le type et la couleur de la notif selon le statut
            $type = 'info';
            $statutDisplay = $nouveauStatut;
            
            switch ($nouveauStatut) {
                case 'paid':
                case 'payée': 
                case 'payee':
                    $type = 'success';
                    $statutDisplay = 'Payée';
                    break;
                case 'pending':
                case 'en_attente':
                    $type = 'warning';
                    $statutDisplay = 'En attente';
                    break;
                case 'cancelled':
                case 'annulée':
                case 'annulee':
                    $type = 'danger';
                    $statutDisplay = 'Annulée';
                    break;
            }

            // Utilisateur créateur, ou l'admin
            // Dans ce cas, on notifie l'utilisateur courant, 
            // ou on peut cibler le user_id de la facture si disponible.
            // S'il n'y a pas de user_id dans Invoice, on prend l'user connecté
            $userId = Auth::id() ?? 1;

            Notification::create([
                'user_id'  => $userId,
                'title'    => 'Facture #' . $invoice->invoice_number . ' — ' . $statutDisplay,
                'body'     => 'Client: ' . ($invoice->customer_supplier ?? 'N/A') . ' — Montant: ' . $invoice->total_amount . ' MAD',
                'type'     => $type,
                'category' => 'facture',
                'is_read'  => false,
            ]);
        }
    }
}
