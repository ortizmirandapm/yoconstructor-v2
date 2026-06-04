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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tipo'     => ['required', 'in:trabajador,empresa'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'tipo'     => $request->tipo,
        ]);

        // Crear perfil según tipo
        if ($request->tipo === 'trabajador') {
            \App\Models\Trabajador::create(['user_id' => $user->id]);
        } elseif ($request->tipo === 'empresa') {
            \App\Models\Empresa::create([
                'user_id'       => $user->id,
                'nombre_empresa' => $request->name,
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        return match ($user->tipo) {
            'empresa'    => redirect()->route('empresa.dashboard'),
            'trabajador' => redirect()->route('trabajador.dashboard'),
            default      => redirect()->route('dashboard'),
        };
    }
}
