<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation : toutes les notifications de l'utilisateur
     * Utilise la table personnalisée notifications (avec is_read boolean)
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id')->latest();
    }

    /**
     * Relation : notifications non lues
     */
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id')
            ->where('is_read', false)
            ->latest();
    }

    /**
     * Vérifier si l'utilisateur est un Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Vérifier si l'utilisateur est un Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'superadmin';
    }
}
