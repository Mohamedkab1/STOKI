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
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@stoki.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'status' => 'active',
            ]
        );

        // 2. Créer un Admin par défaut
        $admin = User::updateOrCreate(
            ['email' => 'admin@stoki.com'],
            [
                'name' => 'Admin Stoki',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // 3. Créer des catégories pour l'admin par défaut
        $categories = [
            ['name' => 'Électronique', 'description' => 'Produits électroniques', 'user_id' => $admin->id],
            ['name' => 'Vêtements', 'description' => 'Vêtements et accessoires', 'user_id' => $admin->id],
            ['name' => 'Alimentation', 'description' => 'Produits alimentaires', 'user_id' => $admin->id],
        ];

        foreach ($categories as $catData) {
            Category::updateOrCreate(
                ['name' => $catData['name'], 'user_id' => $catData['user_id']],
                $catData
            );
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
            Product::updateOrCreate(
                ['sku' => $prodData['sku']],
                $prodData
            );
        }

        // 5. Mouvements de stock (on évite de recréer si déjà existant pour le même produit/raison)
        StockMovement::firstOrCreate(
            [
                'product_id' => 1,
                'reason' => 'Réapprovisionnement',
                'quantity' => 10,
            ],
            [
                'type' => 'in',
                'user_id' => $admin->id
            ]
        );
    }
}