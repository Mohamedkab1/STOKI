<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(\App\Http\Requests\Auth\RegisterRequest $request): RedirectResponse
    {
        $status = $request->role === 'admin' ? 'pending' : 'active';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $status,
        ]);

        event(new Registered($user));

        if ($user->status === 'pending') {
            return redirect()->route('login')->with('status', 'Votre compte administrateur est en attente d\'approbation par le Super Admin.');
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
