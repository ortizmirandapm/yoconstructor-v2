<?php

declare(strict_types=1);

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Requests\CambiarPasswordRequest;
use App\Services\ConfiguracionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ConfiguracionController extends Controller
{
    public function __construct(
        private readonly ConfiguracionService $configuracionService,
    ) {}

    public function edit(): View
    {
        $user = auth()->user();
        $empresa = $user->empresa;

        return view('empresa.configuracion', compact('user', 'empresa'));
    }

    public function cambiarPassword(CambiarPasswordRequest $request): RedirectResponse
    {
        $this->configuracionService->cambiarPassword(
            auth()->user(),
            $request->validated('password'),
        );

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

    public function togglePrivacidad(Request $request): RedirectResponse
    {
        $perfilPublico = $request->boolean('perfil_publico');
        $empresa = auth()->user()->empresa;

        if ($empresa) {
            $empresa->update(['perfil_publico' => $perfilPublico]);
        }

        return back()->with('success', 'Configuración de privacidad guardada.');
    }

    public function eliminarCuenta(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm_text' => ['required', 'string', 'in:ELIMINAR'],
        ], [
            'confirm_text.in' => 'Escribí ELIMINAR exactamente para confirmar.',
        ]);

        $this->configuracionService->eliminarCuentaEmpresa(auth()->user());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login?cuenta=baja');
    }
}
