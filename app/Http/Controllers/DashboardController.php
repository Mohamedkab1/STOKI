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
    public function index(Request $request)
    {
        // Statistiques de base
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalStock = Product::sum('quantity');
        
        // Alertes stock
        $lowStockProducts = Product::whereRaw('quantity <= min_stock')->latest()->take(5)->get();
        $lowStockCount = Product::whereRaw('quantity <= min_stock')->count();
        
        // Valeur du stock
        $totalStockValue = Product::sum(DB::raw('price * quantity'));
        
        // Mouvements mensuels (30 derniers jours)
        $monthlyInCount = StockMovement::where('type', 'in')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
            
        // Derniers mouvements pour la table
        $recentMovements = StockMovement::with('product')
            ->latest()
            ->take(5)
            ->get();
            
        // Factures & CA
        $caTotal = Invoice::where('payment_status', 'paid')->sum('total_amount');
        $dernieresFactures = Invoice::with('product')->latest()->take(5)->get();
        
        // --- chart date range ---
        $dateFrom = $request->filled('date_from')
            ? \Carbon\Carbon::parse($request->date_from)->startOfDay()
            : \Carbon\Carbon::now()->subDays(29)->startOfDay();
            
        $dateTo = $request->filled('date_to')
            ? \Carbon\Carbon::parse($request->date_to)->endOfDay()
            : \Carbon\Carbon::now()->endOfDay();
            
        // --- build daily data between dateFrom and dateTo ---
        $period = \Carbon\Carbon::parse($dateFrom)->daysUntil(\Carbon\Carbon::parse($dateTo)->addDay());
        
        $entriesByDay = StockMovement::where('type', 'in')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->groupBy('date')
            ->pluck('total', 'date');
            
        $exitsByDay = StockMovement::where('type', 'out')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->groupBy('date')
            ->pluck('total', 'date');
            
        $chartLabels  = [];
        $chartEntrees = [];
        $chartSorties = [];
        
        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $chartLabels[]  = $date->format('d/m');
            $chartEntrees[] = (int) ($entriesByDay[$key] ?? 0);
            $chartSorties[] = (int) ($exitsByDay[$key] ?? 0);
        }
        
        $totalEntrees = array_sum($chartEntrees);
        $totalSorties = array_sum($chartSorties);
        
        // Stats Super Admin (si applicable)
        $totalAdmins = \App\Models\User::where('role', 'admin')->count();
        $pendingAdmins = \App\Models\User::where('role', 'admin')->where('status', 'pending')->count();
        
        return view('dashboard.index', compact(
            'totalProducts',
            'totalCategories',
            'totalStock',
            'lowStockCount',
            'lowStockProducts',
            'totalStockValue',
            'monthlyInCount',
            'recentMovements',
            'caTotal',
            'dernieresFactures',
            'chartLabels',
            'chartEntrees',
            'chartSorties',
            'totalEntrees',
            'totalSorties',
            'dateFrom',
            'dateTo',
            'totalAdmins',
            'pendingAdmins'
        ));
    }
}