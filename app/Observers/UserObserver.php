<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if ($user->role === 'admin' && $user->status === 'pending') {
            // Trouver le Super Admin (ou tous les superadmins)
            $superAdmins = User::where('role', 'superadmin')->get();

            foreach ($superAdmins as $superAdmin) {
                \App\Models\Notification::create([
                    'user_id' => $superAdmin->id,
                    'title' => 'Nouvelle demande d\'inscription',
                    'body' => "L'utilisateur {$user->name} ({$user->email}) a demandé un accès Administrateur.",
                    'type' => 'warning',
                    'category' => 'system',
                ]);
            }
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
