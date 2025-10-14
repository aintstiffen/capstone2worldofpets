<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // Password: require confirmation and minimum length (8). Allow any characters.
            'password' => ['required', 'confirmed', Rules\Password::defaults(), 'min:8'],
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Dispatch the Registered event (sends email verification if enabled)
        // - Skip in non-production to avoid mail failures during local/dev testing
        // - In production, catch and report mail errors but don't block registration
        if (app()->environment('production')) {
            try {
                event(new Registered($user));
            } catch (\Throwable $e) {
                \Log::warning('Registration mail dispatch failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                session()->flash('warning', 'We couldn\'t send a verification email right now. You can continue and verify later.');
            }
        } else {
            \Log::info('Skipping email verification dispatch in non-production environment', [
                'user_id' => $user->id,
                'env' => app()->environment(),
            ]);
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}