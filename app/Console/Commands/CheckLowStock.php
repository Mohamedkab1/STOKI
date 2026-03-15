<?php
// app/Console/Commands/CheckLowStock.php

namespace App\Console\Commands;

use App\Models\Product;
use App\Notifications\LowStockNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check-low';
    protected $description = 'Vérifie les produits en stock faible et envoie des notifications';

    public function handle()
    {
        $lowStockProducts = Product::whereRaw('quantity <= min_stock')->get();
        
        foreach ($lowStockProducts as $product) {
            // Envoyer à tous les utilisateurs
            $users = \App\Models\User::all();
            Notification::send($users, new LowStockNotification($product));
        }
        
        $this->info($lowStockProducts->count() . ' produits en stock faible ont été notifiés.');
    }
}