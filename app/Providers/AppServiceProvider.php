<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StockMovement;
use App\Observers\MouvementObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Enregistrement de l'observer pour les mouvements de stock.
     * À chaque création d'un StockMovement, le MouvementObserver
     * génère automatiquement la notification correspondante.
     */
    public function boot(): void
    {
        // Forcer HTTPS en production pour éviter les erreurs de "Mixed Content" sur Railway
        if (config('app.env') === 'production' || env('FORCE_HTTPS', false)) {
            \URL::forceScheme('https');
        }

        // Observer des mouvements de stock → génère des notifications
        StockMovement::observe(MouvementObserver::class);
        
        // Observer des factures → génère des notifications de category facture
        \App\Models\Invoice::observe(\App\Observers\FactureObserver::class);

        // Observer des utilisateurs → notifie le Super Admin des inscriptions
        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }
}
