<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Créer des catégories
        $categories = [
            ['name' => 'Électronique', 'description' => 'Produits électroniques'],
            ['name' => 'Vêtements', 'description' => 'Vêtements et accessoires'],
            ['name' => 'Alimentation', 'description' => 'Produits alimentaires'],
            ['name' => 'Livres', 'description' => 'Livres et magazines'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Créer des produits
        $products = [
            [
                'name' => 'Smartphone XYZ',
                'sku' => 'SM-001',
                'description' => 'Dernier modèle de smartphone',
                'price' => 599.99,
                'quantity' => 50,
                'min_stock' => 10,
                'category_id' => 1
            ],
            [
                'name' => 'T-shirt Coton',
                'sku' => 'VT-001',
                'description' => 'T-shirt 100% coton',
                'price' => 19.99,
                'quantity' => 200,
                'min_stock' => 30,
                'category_id' => 2
            ],
            [
                'name' => 'Pâtes',
                'sku' => 'AL-001',
                'description' => 'Pâtes italiennes',
                'price' => 2.50,
                'quantity' => 500,
                'min_stock' => 100,
                'category_id' => 3
            ],
            [
                'name' => 'Roman Policier',
                'sku' => 'LV-001',
                'description' => 'Meilleure vente',
                'price' => 15.00,
                'quantity' => 30,
                'min_stock' => 5,
                'category_id' => 4
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Créer quelques mouvements de stock
        StockMovement::create([
            'product_id' => 1,
            'type' => 'in',
            'quantity' => 10,
            'reason' => 'Réapprovisionnement'
        ]);

        StockMovement::create([
            'product_id' => 1,
            'type' => 'out',
            'quantity' => 5,
            'reason' => 'Vente'
        ]);
    }
}