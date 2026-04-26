<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminApprovedMail;
use App\Mail\AdminRejectedMail;
use App\Http\Requests\SuperAdmin\UpdateAdminRequest;

class AdminManagementController extends Controller
{
    /**
     * Liste des administrateurs (actifs et en attente)
     */
    public function index()
    {
        $pendingAdmins = User::where('role', 'admin')->where('status', 'pending')->latest()->get();
        $activeAdmins = User::where('role', 'admin')->where('status', 'active')->latest()->get();
        $rejectedAdmins = User::where('role', 'admin')->where('status', 'rejected')->latest()->get();

        $stats = [
            'total' => User::where('role', 'admin')->count(),
            'active' => $activeAdmins->count(),
            'pending' => $pendingAdmins->count(),
            'rejected' => $rejectedAdmins->count(),
        ];

        return view('superadmin.admins.index', compact('pendingAdmins', 'activeAdmins', 'rejectedAdmins', 'stats'));
    }

    /**
     * Formulaire de création d'un admin
     */
    public function create()
    {
        return view('superadmin.admins.create');
    }

    /**
     * Enregistrer un nouvel admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => 'admin',
            'status' => 'active', // Créé directement par le Super Admin -> Actif
        ]);

        return redirect()->route('superadmin.admins.index')->with('success', "Le compte administrateur pour {$validated['name']} a été créé avec succès.");
    }

    /**
     * Approuver une demande
     */
    public function approve(User $admin)
    {
        $admin->update(['status' => 'active']);

        // Envoi de l'email d'approbation
        Mail::to($admin->email)->send(new AdminApprovedMail($admin));

        return redirect()->back()->with('success', "Le compte de {$admin->name} a été approuvé et un email a été envoyé.");
    }

    /**
     * Rejeter une demande
     */
    public function reject(User $admin)
    {
        $admin->update(['status' => 'rejected']);

        // Envoi de l'email de refus
        Mail::to($admin->email)->send(new AdminRejectedMail($admin));

        return redirect()->back()->with('info', "La demande de {$admin->name} a été rejetée et un email a été envoyé.");
    }

    /**
     * Formulaire d'édition
     */
    public function edit(User $admin)
    {
        return view('superadmin.admins.edit', compact('admin'));
    }

    /**
     * Mise à jour des informations
     */
    public function update(UpdateAdminRequest $request, User $admin)
    {
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $admin->update($data);

        return redirect()->route('superadmin.admins.index')->with('success', 'Compte mis à jour avec succès.');
    }

    /**
     * Suppression d'un compte
     */
    public function destroy(User $admin)
    {
        // Empêcher la suppression si c'est un superadmin (sécurité supplémentaire)
        if ($admin->role === 'superadmin') {
            return redirect()->back()->with('error', "Impossible de supprimer un Super Administrateur.");
        }

        $admin->delete();

        return redirect()->route('superadmin.admins.index')->with('success', 'Le compte a été supprimé.');
    }
}
