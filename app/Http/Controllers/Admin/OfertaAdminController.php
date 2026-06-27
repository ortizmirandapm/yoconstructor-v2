<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\OfertaEstado;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOfertaRequest;
use App\Models\Especialidad;
use App\Models\Oferta;
use App\Models\Rubro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class OfertaAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = Oferta::with('empresa', 'rubro')
            ->withCount('postulaciones');

        if ($search = $request->get('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhereHas('empresa', fn ($e) => $e->where('nombre_empresa', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('rubro')) {
            $query->where('rubro_id', $request->rubro);
        }

        if ($request->filled('modalidad')) {
            $query->where('modalidad', $request->modalidad);
        }

        $ofertas = $query->latest()->paginate(15)->withQueryString();

        $rubros = Rubro::where('estado', true)->orderBy('nombre')->get();
        $totalActivas = Oferta::where('estado', OfertaEstado::Activa)->count();
        $totalPausadas = Oferta::where('estado', OfertaEstado::Pausada)->count();
        $totalCerradas = Oferta::where('estado', OfertaEstado::Cerrada)->count();

        return view('admin.ofertas.index', compact(
            'ofertas', 'rubros', 'totalActivas', 'totalPausadas', 'totalCerradas'
        ));
    }

    public function edit(Oferta $oferta): JsonResponse
    {
        $oferta->load('empresa', 'rubro', 'especialidades');

        return response()->json($oferta);
    }

    public function update(UpdateOfertaRequest $request, Oferta $oferta): RedirectResponse
    {
        $oferta->update($request->validated());

        return redirect()->route('admin.ofertas.index')
            ->with('success', 'Oferta actualizada correctamente.');
    }

    public function cambiarEstado(Oferta $oferta): RedirectResponse
    {
        $nuevoEstado = $oferta->estado === OfertaEstado::Activa
            ? OfertaEstado::Pausada
            : OfertaEstado::Activa;

        $oferta->update(['estado' => $nuevoEstado]);

        $mensaje = $nuevoEstado === OfertaEstado::Activa
            ? 'Oferta activada correctamente.'
            : 'Oferta pausada correctamente.';

        return redirect()->route('admin.ofertas.index')->with('success', $mensaje);
    }

    public function destroy(Oferta $oferta): RedirectResponse
    {
        $oferta->delete();

        return redirect()->route('admin.ofertas.index')
            ->with('success', 'Oferta eliminada correctamente.');
    }
}
