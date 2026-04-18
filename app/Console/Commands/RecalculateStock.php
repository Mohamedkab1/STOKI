<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Console\Command;

class RecalculateStock extends Command
{
    protected $signature = 'stock:recalculate';
    protected $description = 'Recalculer tous les stocks à partir des mouvements';

    public function handle()
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            $entries = StockMovement::where('product_id', $product->id)->where('type', 'in')->sum('quantity');
            $exits = StockMovement::where('product_id', $product->id)->where('type', 'out')->sum('quantity');
            $calculatedStock = $entries - $exits;
            
            $product->update(['quantity' => $calculatedStock]);
            
            $this->info("Produit: {$product->name} - Nouveau stock: {$calculatedStock}");
        }
        
        $this->info('Stocks recalculés avec succès !');
        return 0;
    }
}
