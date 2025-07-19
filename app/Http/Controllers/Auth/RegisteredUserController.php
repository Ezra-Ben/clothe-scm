<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Get the role from session and fetch its ID from the roles table
        $roleName = session('selected_role');
        $roleId = $roleName ? Role::where('name', $roleName)->value('id') : null;


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Custom redirect logic after registration
        if ($user->hasRole('customer')) {
            return redirect()->route('home');
        }
        if ($user->hasRole('supplier')) {
            return redirect()->route('vendor.form'); 
        }
        if ($user->hasRole('carrier')) {
            return redirect()->route('welcome');
        }

        return redirect()->route('welcome', ['absolute' => false]);
    }
}
