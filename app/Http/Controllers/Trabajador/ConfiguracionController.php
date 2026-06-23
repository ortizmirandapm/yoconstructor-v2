<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ConfiguracionController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('trabajador.configuracion', compact('user'));
    }

    public function toggleVisibilidad(Request $request)
    {
        $request->validate([
            'visible_busqueda' => 'required|in:0,1',
        ]);

        $nuevoValor = $request->boolean('visible_busqueda');
        auth()->user()->update(['visible_busqueda' => $nuevoValor]);

        $mensaje = $nuevoValor
            ? '✓ Ahora aparecés en la búsqueda de empresas.'
            : '✓ Ya no aparecés en la búsqueda de empresas.';

        return back()->with('success', $mensaje);
    }

    public function eliminarCuenta(Request $request)
    {
        $request->validate([
            'confirmar_texto' => 'required|string',
        ]);

        if ($request->input('confirmar_texto') !== 'ELIMINAR') {
            return back()->with('error', 'Escribí ELIMINAR exactamente para confirmar.');
        }

        auth()->user()->update(['estado' => false]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/?cuenta=eliminada');
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('La contraseña actual no es correcta.');
                }
            }],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', '✓ Contraseña actualizada correctamente.');
    }
}
