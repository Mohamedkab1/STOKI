<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur des Notifications
 *
 * Gère l'affichage, le filtrage par catégories, le marquage 
 * comme lu et le comptage pour l'utilisateur authentifié.
 */
class NotificationController extends Controller
{
    /**
     * Récupérer les notifications récentes (toutes ou par défaut)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse 
    {
        if (!Auth::check()) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $notifications = Notification::forUser(Auth::id())
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($notif) {
                return $this->formatNotification($notif);
            });

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    /**
     * Compter le total de non-lues et par catégorie (JSON)
     *
     * @return JsonResponse
     */
    public function unreadCount(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['total' => 0, 'entree' => 0, 'sortie' => 0, 'alerte_stock' => 0, 'facture' => 0]);
        }

        $userId = Auth::id();
        $total = Notification::forUser($userId)->unread()->count();
        $entree = Notification::forUser($userId)->where('category', 'entree')->unread()->count();
        $sortie = Notification::forUser($userId)->where('category', 'sortie')->unread()->count();
        $alerteStock = Notification::forUser($userId)->where('category', 'alerte_stock')->unread()->count();
        $facture = Notification::forUser($userId)->where('category', 'facture')->unread()->count();

        return response()->json([
            'total'        => $total,
            'entree'       => $entree,
            'sortie'       => $sortie,
            'alerte_stock' => $alerteStock,
            'facture'      => $facture,
        ]);
    }

    /**
     * Obtenir les notifications par catégorie
     *
     * @param string $category
     * @return JsonResponse
     */
    public function byCategory($category): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['notifications' => []]);
        }

        $notifications = Notification::forUser(Auth::id())
            ->where('category', $category)
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($notif) {
                return $this->formatNotification($notif);
            });

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    /**
     * Marquer une notification comme lue
     *
     * @param int $id
     * @return JsonResponse
     */
    public function markAsRead($id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues (par catégorie optionnelle via Request)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        $query = Notification::forUser(Auth::id())->unread();

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $query->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Formater les données de la notification pour JSON
     */
    private function formatNotification($notif): array
    {
        // On surcharge l'icône par catégorie selon les règles demandées si possible
        $icon = $notif->icon; // fallback
        switch ($notif->category) {
            case 'entree':
                $icon = 'fas fa-arrow-down';
                break;
            case 'sortie':
                $icon = 'fas fa-arrow-up';
                break;
            case 'alerte_stock':
                $icon = 'fas fa-exclamation-triangle';
                break;
            case 'facture':
                $icon = 'fas fa-file-invoice';
                break;
        }

        return [
            'id'         => $notif->id,
            'title'      => $notif->title,
            'body'       => $notif->body,
            'type'       => $notif->type,
            'category'   => $notif->category,
            'icon'       => $icon,
            'is_read'    => $notif->is_read,
            'time_ago'   => $notif->time_ago ?? $notif->created_at->diffForHumans(),
            'created_at' => $notif->created_at->toISOString(),
        ];
    }
    
    // ======== ROUTES POUR LES VUES (Gardées pour compatibilité avec le reste) ======== 
    
    public function showAll(Request $request)
    {
        if (!Auth::check()) return redirect()->route('login');
        
        $query = Notification::forUser(Auth::id())->latest();
        if ($request->filled('type')) $query->ofType($request->type);
        if ($request->filled('category')) $query->where('category', $request->category);

        $notifications = $query->paginate(15);
        $unreadCount = Notification::forUser(Auth::id())->unread()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function show($id)
    {
        if (!Auth::check()) return redirect()->route('login');
        $notification = Notification::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if ($notification->isUnread()) $notification->markAsRead();
        return view('notifications.show', compact('notification'));
    }

    public function destroy($id)
    {
        if (!Auth::check()) return response()->json(['success' => false], 401);
        Notification::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Notification supprimée.');
    }

    public function destroyAll()
    {
        if (!Auth::check()) return response()->json(['success' => false], 401);
        Notification::forUser(Auth::id())->delete();
        return back()->with('success', 'Toutes les notifications ont été supprimées.');
    }
}