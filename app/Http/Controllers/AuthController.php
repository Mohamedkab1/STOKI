<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    // app/Http/Controllers/AuthController.php
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                $message = $user->status === 'pending' 
                    ? 'Votre compte est en attente d\'approbation par le Super Admin.' 
                    : 'Votre compte a été rejeté.';
                
                return back()->withErrors(['email' => $message])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription
     */
    public function register(\App\Http\Requests\Auth\RegisterRequest $request)
    {
        $status = $request->role === 'admin' ? 'pending' : 'active';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $status,
        ]);

        if ($user->status === 'pending') {
            return redirect()->route('login')->with('status', 'Votre compte administrateur a été créé et est en attente d\'approbation.');
        }

        Auth::login($user);

        return redirect('/')
            ->with('success', 'Compte créé avec succès ! Bienvenue ' . $user->name);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}