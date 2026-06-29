<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRubroRequest;
use App\Http\Requests\Admin\UpdateRubroRequest;
use App\Models\Rubro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class RubroController extends Controller
{
    public function index(Request $request): View
    {
        $query = Rubro::withCount('ofertas');

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

        $rubros = $query->latest()->paginate(15)->withQueryString();

        $totalActivos = Rubro::where('estado', true)->count();
        $totalInactivos = Rubro::where('estado', false)->count();

        return view('admin.rubros.index', compact('rubros', 'totalActivos', 'totalInactivos'));
    }

    public function store(StoreRubroRequest $request): RedirectResponse
    {
        Rubro::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => true,
        ]);

        return redirect()->route('admin.rubros.index')
            ->with('success', 'Rubro creado correctamente.');
    }

    public function edit(Rubro $rubro): JsonResponse
    {
        return response()->json($rubro);
    }

    public function update(UpdateRubroRequest $request, Rubro $rubro): RedirectResponse
    {
        $rubro->update($request->validated());

        return redirect()->route('admin.rubros.index')
            ->with('success', 'Rubro actualizado correctamente.');
    }

    public function cambiarEstado(Rubro $rubro): RedirectResponse
    {
        $rubro->update(['estado' => ! $rubro->estado]);

        $mensaje = $rubro->estado
            ? 'Rubro activado correctamente.'
            : 'Rubro desactivado correctamente.';

        return redirect()->route('admin.rubros.index')->with('success', $mensaje);
    }

    public function destroy(Rubro $rubro): RedirectResponse
    {
        $rubro->delete();

        return redirect()->route('admin.rubros.index')
            ->with('success', 'Rubro eliminado correctamente.');
    }
}
