<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Afficher la page de profil
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('profile.index', compact('user'));
    }

    /**
     * Mettre à jour les infos personnelles
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'language' => 'nullable|string|in:fr,ar,en',
            'timezone' => 'nullable|string',
            'date_format' => 'nullable|string',
        ]);

        $user->update($validated);

        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }
}