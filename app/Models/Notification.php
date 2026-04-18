<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Notification — Table personnalisée
 *
 * Colonnes : id, user_id, title, body, type (info/success/warning/danger), is_read, created_at
 * Chaque notification est liée à un utilisateur via user_id.
 */
class Notification extends Model
{
    /**
     * Nom de la table
     */
    protected $table = 'notifications';

    /**
     * Colonnes assignables en masse
     */
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'type',
        'category',
        'is_read',
    ];

    /**
     * Casting des attributs
     */
    protected $casts = [
        'is_read'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Valeurs par défaut
     */
    protected $attributes = [
        'type'    => 'info',
        'is_read' => false,
    ];

    // -----------------------------------------------------------------
    //  RELATIONS
    // -----------------------------------------------------------------

    /**
     * Relation vers l'utilisateur propriétaire
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // -----------------------------------------------------------------
    //  SCOPES
    // -----------------------------------------------------------------

    /**
     * Scope : notifications non lues uniquement
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope : notifications lues uniquement
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope : notifications d'un utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope : filtrer par type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // -----------------------------------------------------------------
    //  MÉTHODES
    // -----------------------------------------------------------------

    /**
     * Marquer cette notification comme lue
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Vérifier si la notification est non lue
     */
    public function isUnread(): bool
    {
        return !$this->is_read;
    }

    /**
     * Obtenir l'icône Font Awesome selon le type
     */
    public function getIconAttribute(): string
    {
        $icons = [
            'info'    => 'fas fa-info-circle',
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'danger'  => 'fas fa-times-circle',
        ];

        return $icons[$this->type] ?? $icons['info'];
    }

    /**
     * Obtenir le temps relatif en français (il y a X min)
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->locale('fr')->diffForHumans();
    }
}
