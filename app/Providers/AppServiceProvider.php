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
        // Observer des mouvements de stock → génère des notifications
        StockMovement::observe(MouvementObserver::class);
        
        // Observer des factures → génère des notifications de category facture
        \App\Models\Invoice::observe(\App\Observers\FactureObserver::class);
    }
}
