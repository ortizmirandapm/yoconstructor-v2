<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Http\Requests\CambiarPasswordRequest;
use App\Http\Requests\ToggleVisibilidadRequest;
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
        return view('trabajador.configuracion', compact('user'));
    }

    public function toggleVisibilidad(ToggleVisibilidadRequest $request): RedirectResponse
    {
        $nuevoValor = $request->boolean('visible_busqueda');
        $this->configuracionService->toggleVisibilidad(auth()->user(), $nuevoValor);

        $mensaje = $nuevoValor
            ? '✓ Ahora aparecés en la búsqueda de empresas.'
            : '✓ Ya no aparecés en la búsqueda de empresas.';

        return back()->with('success', $mensaje);
    }

    public function eliminarCuenta(Request $request): RedirectResponse
    {
        $request->validate([
            'confirmar_texto' => ['required', 'string', 'in:ELIMINAR'],
        ], [
            'confirmar_texto.in' => 'Escribí ELIMINAR exactamente para confirmar.',
        ]);

        $this->configuracionService->eliminarCuenta(auth()->user());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/?cuenta=eliminada');
    }

    public function cambiarPassword(CambiarPasswordRequest $request): RedirectResponse
    {
        $this->configuracionService->cambiarPassword(
            auth()->user(),
            $request->validated('password'),
        );

        return back()->with('success', '✓ Contraseña actualizada correctamente.');
    }
}
