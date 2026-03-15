<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques de base
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalStock = Product::sum('quantity');
        $lowStockProducts = Product::whereRaw('quantity <= min_stock')->count();
        
        // Derniers mouvements
        $recentMovements = StockMovement::with('product')
            ->latest()
            ->take(10)
            ->get();
        
        // Valeur du stock
        $stockValue = Product::sum(DB::raw('price * quantity'));
        
        // Données pour le graphique à barres (évolution du stock sur 7 jours)
        $stockEvolution = $this->getStockEvolution();
        
        // Produits les plus vendus
        $topProducts = Product::withCount(['stockMovements as total_sold' => function($query) {
                $query->where('type', 'out')
                      ->select(DB::raw('SUM(quantity)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
        
        // Dernières factures
        $recentInvoices = Invoice::with('product')
            ->latest()
            ->take(5)
            ->get();
        
        // Distribution par catégorie (pour un autre graphique)
        $categoryDistribution = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->get();
        
        return view('dashboard.index', compact(
            'totalProducts',
            'totalCategories',
            'totalStock',
            'lowStockProducts',
            'recentMovements',
            'stockValue',
            'topProducts',
            'recentInvoices',
            'stockEvolution',
            'categoryDistribution'
        ));
    }

    /**
     * Récupérer l'évolution du stock sur les 7 derniers jours
     */
    private function getStockEvolution()
    {
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            
            // Total des entrées ce jour-là
            $entries = StockMovement::whereDate('created_at', $date)
                ->where('type', 'in')
                ->sum('quantity');
            
            // Total des sorties ce jour-là
            $exits = StockMovement::whereDate('created_at', $date)
                ->where('type', 'out')
                ->sum('quantity');
            
            // Stock net = entrées - sorties
            $netStock = $entries - $exits;
            
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'entries' => $entries,
                'exits' => $exits,
                'net' => $netStock
            ];
        }
        
        return [
            'labels' => $labels,
            'entries' => array_column($data, 'entries'),
            'exits' => array_column($data, 'exits'),
            'net' => array_column($data, 'net')
        ];
    }
}