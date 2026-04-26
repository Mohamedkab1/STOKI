<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Créer le Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@stoki.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        // 2. Créer un Admin par défaut
        $admin = User::create([
            'name' => 'Admin Stoki',
            'email' => 'admin@stoki.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 3. Créer des catégories pour l'admin par défaut
        $categories = [
            ['name' => 'Électronique', 'description' => 'Produits électroniques', 'user_id' => $admin->id],
            ['name' => 'Vêtements', 'description' => 'Vêtements et accessoires', 'user_id' => $admin->id],
            ['name' => 'Alimentation', 'description' => 'Produits alimentaires', 'user_id' => $admin->id],
        ];

        foreach ($categories as $catData) {
            Category::create($catData);
        }

        // 4. Créer des produits pour l'admin par défaut
        $products = [
            [
                'name' => 'Smartphone XYZ',
                'sku' => 'SM-001',
                'description' => 'Dernier modèle de smartphone',
                'price' => 599.99,
                'quantity' => 50,
                'min_stock' => 10,
                'category_id' => 1,
                'user_id' => $admin->id,
                'image' => 'products/d50A9TaVWBqq4KDkMhS1ShDoJWaBk3OsmG6QNNQZ.png'
            ],
            [
                'name' => 'T-shirt Coton',
                'sku' => 'VT-001',
                'description' => 'T-shirt 100% coton',
                'price' => 19.99,
                'quantity' => 200,
                'min_stock' => 30,
                'category_id' => 2,
                'user_id' => $admin->id,
                'image' => 'products/EO1kFFpt4LXaeBYm2bhWfqUdEpnFEgjmxN4sFeDD.png'
            ],
        ];

        foreach ($products as $prodData) {
            Product::create($prodData);
        }

        // 5. Mouvements de stock
        StockMovement::create([
            'product_id' => 1,
            'type' => 'in',
            'quantity' => 10,
            'reason' => 'Réapprovisionnement',
            'user_id' => $admin->id
        ]);
    }
}