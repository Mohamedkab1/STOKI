<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // SUPPRIMEZ COMPLÈTEMENT LE CONSTRUCTEUR
    // Pas de __construct() du tout

    /**
     * Afficher toutes les notifications
     */
    public function index(Request $request)
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        $user = Auth::user();
        
        $query = $user->notifications();
        
        // Filtre par type si présent
        if ($request->filled('type')) {
            $type = $request->type;
            $query->where('type', 'like', '%' . $type . '%');
        }
        
        $notifications = $query->paginate(15);
        $unreadCount = $user->unreadNotifications->count();
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Obtenir les dernières notifications pour le dropdown
     */
    public function getLatest()
    {
        if (!Auth::check()) {
            return response()->json([
                'notifications' => [],
                'unread_count' => 0
            ]);
        }

        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;
                return [
                    'id' => $notification->id,
                    'data' => [
                        'message' => $data['message'] ?? 'Nouvelle notification',
                        'icon' => $data['icon'] ?? 'bell',
                        'color' => $data['color'] ?? 'secondary',
                        'type' => $data['type'] ?? 'general',
                        'action_url' => $data['action_url'] ?? '#',
                        'product_name' => $data['product_name'] ?? null,
                        'quantity' => $data['quantity'] ?? null,
                        'total_amount' => $data['total_amount'] ?? null,
                        'current_stock' => $data['current_stock'] ?? null,
                        'min_stock' => $data['min_stock'] ?? null,
                        'movement_type' => $data['movement_type'] ?? null,
                        'invoice_number' => $data['invoice_number'] ?? null
                    ],
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->toISOString()
                ];
            });
        
        $unreadCount = $user->unreadNotifications->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        Auth::user()->unreadNotifications->markAsRead();
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Afficher une notification spécifique
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $notification = Auth::user()->notifications()->findOrFail($id);
        
        if ($notification->unread()) {
            $notification->markAsRead();
        }
        
        $data = $notification->data;
        
        return view('notifications.show', compact('notification', 'data'));
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification supprimée.');
    }

    /**
     * Supprimer toutes les notifications
     */
    public function destroyAll()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        Auth::user()->notifications()->delete();
        
        return back()->with('success', 'Toutes les notifications ont été supprimées.');
    }

    /**
     * Obtenir le nombre de notifications non lues
     */
    public function getUnreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Auth::user()->unreadNotifications->count();
        
        return response()->json(['count' => $count]);
    }
}