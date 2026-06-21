<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Especialidad;
use App\Models\Provincia;
use App\Models\Rubro;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Trabajador;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'provincias'     => Provincia::where('estado', true)->orderBy('nombre')->get(),
            'rubros'         => Rubro::where('estado', true)->orderBy('orden')->orderBy('nombre')->get(),
            'especialidades' => Especialidad::where('estado', true)->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tipo = $request->input('tipo');

        $commonRules = [
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tipo'     => ['required', 'in:trabajador,empresa'],
        ];

        if ($tipo === 'trabajador') {
            $rules = array_merge($commonRules, [
                'nombre'          => ['required', 'string', 'max:50'],
                'apellido'        => ['required', 'string', 'max:50'],
                'dni'             => ['required', 'string', 'max:20', 'unique:trabajadores,dni'],
                'especialidad_id' => ['required', 'exists:especialidades,id'],
            ]);

            $request->validate($rules);

            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name'     => $request->nombre . ' ' . $request->apellido,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'tipo'     => 'trabajador',
                    'estado'   => true,
                ]);

                $trabajador = Trabajador::create([
                    'user_id'  => $user->id,
                    'nombre'   => $request->nombre,
                    'apellido' => $request->apellido,
                    'dni'      => $request->dni,
                ]);

                $trabajador->especialidades()->attach($request->especialidad_id, [
                    'es_principal'     => true,
                    'nivel_experiencia' => 'Básico',
                ]);

                event(new Registered($user));
                Auth::login($user);
            });
        } else {
            $rules = array_merge($commonRules, [
                'nombre_empresa' => ['required', 'string', 'max:200'],
                'razon_social'   => ['nullable', 'string', 'max:100'],
                'cuit'           => ['required', 'string', 'max:20', 'unique:empresas,cuit'],
                'rubro_id'       => ['required', 'exists:rubros,id'],
                'provincia_id'   => ['required', 'exists:provincias,id'],
                'telefono'       => ['nullable', 'string', 'max:20'],
                'email_contacto' => ['nullable', 'string', 'email', 'max:100'],
            ]);

            $request->validate($rules);

            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name'     => $request->nombre_empresa,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'tipo'     => 'empresa',
                    'estado'   => true,
                ]);

                Empresa::create([
                    'user_id'       => $user->id,
                    'nombre_empresa' => $request->nombre_empresa,
                    'razon_social'   => $request->razon_social,
                    'cuit'           => $request->cuit,
                    'rubro_id'       => $request->rubro_id,
                    'provincia_id'   => $request->provincia_id,
                    'telefono'       => $request->telefono,
                    'email_contacto' => $request->email_contacto,
                    'estado'         => 'activo',
                ]);

                event(new Registered($user));
                Auth::login($user);
            });
        }

        return match (auth()->user()->tipo) {
            'empresa'    => redirect()->intended(route('empresa.dashboard')),
            'trabajador' => redirect()->intended(route('home')),
            default      => redirect()->intended(route('home')),
        };
    }
}
