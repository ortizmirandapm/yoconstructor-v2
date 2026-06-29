<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTrabajadorRequest;
use App\Http\Requests\Admin\UpdateTrabajadorRequest;
use App\Models\Especialidad;
use App\Models\Provincia;
use App\Models\Trabajador;
use App\Services\Admin\AdminTrabajadorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class TrabajadorController extends Controller
{
    public function __construct(
        private readonly AdminTrabajadorService $trabajadorService,
    ) {}

    public function index(Request $request): View
    {
        $query = Trabajador::with('user', 'provincia', 'especialidades')
            ->withCount('postulaciones');

        if ($search = $request->get('buscar')) {
            $query->search($search);
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->whereHas('user', fn ($u) => $u->where('estado', true));
            } elseif ($request->estado === 'inactivos') {
                $query->whereHas('user', fn ($u) => $u->where('estado', false));
            }
        }

        if ($request->filled('especialidad')) {
            $query->whereHas('especialidades', fn ($q) => $q->where('especialidades.id', $request->especialidad));
        }

        if ($request->boolean('con_cv')) {
            $query->whereNotNull('curriculum_pdf')->where('curriculum_pdf', '!=', '');
        }

        $trabajadores = $query->latest()->paginate(15)->withQueryString();

        $especialidades = Especialidad::where('estado', true)->orderBy('nombre')->get();
        $provincias = Provincia::where('estado', true)->orderBy('nombre')->get();

        $trabajadoresIds = $trabajadores->pluck('id');
        $totalActivos = Trabajador::whereHas('user', fn ($u) => $u->where('estado', true))->count();
        $totalInactivos = Trabajador::whereHas('user', fn ($u) => $u->where('estado', false))->count();

        return view('admin.trabajadores.index', compact(
            'trabajadores', 'especialidades', 'provincias',
            'totalActivos', 'totalInactivos'
        ));
    }

    public function store(StoreTrabajadorRequest $request): RedirectResponse
    {
        $this->trabajadorService->crear($request->validated());

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador creado correctamente.');
    }

    public function edit(Trabajador $trabajador): JsonResponse
    {
        $trabajador->load('user');

        return response()->json($trabajador);
    }

    public function update(UpdateTrabajadorRequest $request, Trabajador $trabajador): RedirectResponse
    {
        $this->trabajadorService->actualizar(
            $trabajador,
            $request->validated(),
            $request->filled('password') ? $request->password : null,
        );

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador actualizado correctamente.');
    }

    public function cambiarEstado(Trabajador $trabajador): RedirectResponse
    {
        $this->trabajadorService->cambiarEstado($trabajador);

        $mensaje = $trabajador->fresh()->user->estado
            ? 'Trabajador activado correctamente.'
            : 'Trabajador desactivado correctamente.';

        return redirect()->route('admin.trabajadores.index')->with('success', $mensaje);
    }

    public function destroy(Trabajador $trabajador): RedirectResponse
    {
        $this->trabajadorService->eliminar($trabajador);

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador eliminado correctamente.');
    }
}
