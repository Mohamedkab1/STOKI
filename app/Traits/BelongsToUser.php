<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToUser
{
    /**
     * Boot the trait
     */
    protected static function bootBelongsToUser()
    {
        // Automatiquement ajouter user_id lors de la création
        static::creating(function ($model) {
            if (Auth::check() && !$model->user_id) {
                $model->user_id = Auth::id();
            }
        });

        // Appliquer un scope global pour filtrer par utilisateur
        // Sauf si l'utilisateur est un Super Admin
        static::addGlobalScope('user_filter', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();
                if (!$user->isSuperAdmin()) {
                    $builder->where('user_id', $user->id);
                }
            }
        });
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
