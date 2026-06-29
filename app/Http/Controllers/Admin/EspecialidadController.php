<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEspecialidadRequest;
use App\Http\Requests\Admin\UpdateEspecialidadRequest;
use App\Models\Especialidad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EspecialidadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Especialidad::withCount('trabajadores', 'ofertas');

        if ($search = $request->get('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->where('estado', true);
            } elseif ($request->estado === 'inactivos') {
                $query->where('estado', false);
            }
        }

        $especialidades = $query->latest()->paginate(15)->withQueryString();

        $totalActivos = Especialidad::where('estado', true)->count();
        $totalInactivos = Especialidad::where('estado', false)->count();

        return view('admin.especialidades.index', compact('especialidades', 'totalActivos', 'totalInactivos'));
    }

    public function store(StoreEspecialidadRequest $request): RedirectResponse
    {
        Especialidad::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => true,
        ]);

        return redirect()->route('admin.especialidades.index')
            ->with('success', 'Especialidad creada correctamente.');
    }

    public function edit(Especialidad $especialidad): JsonResponse
    {
        return response()->json($especialidad);
    }

    public function update(UpdateEspecialidadRequest $request, Especialidad $especialidad): RedirectResponse
    {
        $especialidad->update($request->validated());

        return redirect()->route('admin.especialidades.index')
            ->with('success', 'Especialidad actualizada correctamente.');
    }

    public function cambiarEstado(Especialidad $especialidad): RedirectResponse
    {
        $especialidad->update(['estado' => ! $especialidad->estado]);

        $mensaje = $especialidad->estado
            ? 'Especialidad activada correctamente.'
            : 'Especialidad desactivada correctamente.';

        return redirect()->route('admin.especialidades.index')->with('success', $mensaje);
    }

    public function destroy(Especialidad $especialidad): RedirectResponse
    {
        $especialidad->delete();

        return redirect()->route('admin.especialidades.index')
            ->with('success', 'Especialidad eliminada correctamente.');
    }
}
