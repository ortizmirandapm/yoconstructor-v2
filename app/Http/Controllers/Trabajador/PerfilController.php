<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrabajadorPerfilUpdateRequest;
use App\Models\Especialidad;
use App\Models\Localidad;
use App\Models\Provincia;
use App\Services\TrabajadorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class PerfilController extends Controller
{
    public function __construct(
        private readonly TrabajadorService $trabajadorService,
    ) {}

    public function edit(): View
    {
        $trabajador = auth()->user()->trabajador;
        $especialidades = Especialidad::where('estado', true)->get();
        $provincias = Provincia::all();
        $localidades = $trabajador->provincia_preferencia_id
            ? Localidad::where('provincia_id', $trabajador->provincia_preferencia_id)->get()
            : collect();

        return view('trabajador.perfil', compact('trabajador', 'especialidades', 'provincias', 'localidades'));
    }

    public function update(TrabajadorPerfilUpdateRequest $request): RedirectResponse
    {
        $trabajador = auth()->user()->trabajador;

        $this->trabajadorService->actualizarPerfil($trabajador, $request);

        return redirect()->route('trabajador.perfil.edit')->with('success', 'Perfil actualizado correctamente.');
    }
}
