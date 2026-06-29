<?php

declare(strict_types=1);

namespace App\Http\Controllers\Empresa;

use App\Enums\OfertaEstado;
use App\Http\Controllers\Controller;
use App\Http\Requests\OfertaStoreRequest;
use App\Http\Requests\OfertaUpdateRequest;
use App\Models\Especialidad;
use App\Models\Oferta;
use App\Models\Provincia;
use App\Models\Rubro;
use App\Services\OfertaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class OfertaController extends Controller
{
    public function __construct(
        private readonly OfertaService $ofertaService,
    ) {}

    private function getEmpresa()
    {
        return auth()->user()->empresa;
    }

    public function index(Request $request): View
    {
        $empresa = $this->getEmpresa();

        $query = $empresa->ofertas()
            ->where('estado', '!=', OfertaEstado::Borrador)
            ->with('especialidades', 'provincia')
            ->withCount('postulaciones');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search): void {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('especialidad')) {
            $query->whereHas('especialidades', fn ($q) => $q->where('especialidades.id', $request->input('especialidad'))
            );
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('provincia')) {
            $query->where('provincia_id', $request->input('provincia'));
        }

        $order = $request->input('orden', 'desc') === 'asc' ? 'ASC' : 'DESC';
        $ofertas = $query->orderBy('created_at', $order)->paginate(12)->withQueryString();

        $especialidades = Especialidad::where('estado', true)->get();
        $provincias = Provincia::all();

        return view('empresa.ofertas.index', compact('ofertas', 'especialidades', 'provincias'));
    }

    public function create(): View
    {
        $especialidades = Especialidad::where('estado', true)->get();
        $provincias = Provincia::all();

        return view('empresa.ofertas.create', compact('especialidades', 'provincias'));
    }

    public function store(OfertaStoreRequest $request): RedirectResponse
    {
        $data = $request->except('especialidades', 'especialidad_principal');

        $this->ofertaService->crear(
            $data,
            $this->getEmpresa(),
            $request->input('especialidades', []),
            $request->filled('especialidad_principal') ? (int) $request->input('especialidad_principal') : null,
        );

        return redirect()->route('empresa.ofertas.index')->with('success', 'Oferta publicada.');
    }

    public function edit(Oferta $oferta): View
    {
        $this->authorize('update', $oferta);

        $especialidades = Especialidad::where('estado', true)->get();
        $rubros = Rubro::where('estado', true)->get();
        $provincias = Provincia::all();

        return view('empresa.ofertas.edit', compact('oferta', 'especialidades', 'rubros', 'provincias'));
    }

    public function update(OfertaUpdateRequest $request, Oferta $oferta): RedirectResponse
    {
        $this->authorize('update', $oferta);

        $data = $request->except('especialidades', 'especialidad_principal');

        $this->ofertaService->actualizar(
            $oferta,
            $data,
            $request->input('especialidades', []),
            $request->filled('especialidad_principal') ? (int) $request->input('especialidad_principal') : null,
        );

        return redirect()->route('empresa.ofertas.index')->with('success', 'Oferta actualizada.');
    }

    public function toggleEstado(Oferta $oferta): RedirectResponse
    {
        $this->authorize('update', $oferta);

        $nuevoEstado = $oferta->estado === OfertaEstado::Activa
            ? OfertaEstado::Pausada
            : OfertaEstado::Activa;

        $oferta->update(['estado' => $nuevoEstado]);

        $mensaje = $nuevoEstado === OfertaEstado::Pausada
            ? 'Oferta pausada correctamente.'
            : 'Oferta activada correctamente.';

        return redirect()->back()->with('toast', $mensaje);
    }

    public function destroy(Oferta $oferta): RedirectResponse
    {
        $this->authorize('delete', $oferta);

        $oferta->update(['estado' => OfertaEstado::Borrador]);

        return redirect()->route('empresa.ofertas.index')->with('toast', 'Oferta movida a Borradores.');
    }

    public function borradores(Request $request): View
    {
        $empresa = $this->getEmpresa();

        $query = $empresa->ofertas()
            ->where('estado', OfertaEstado::Borrador)
            ->with('especialidades', 'provincia')
            ->withCount('postulaciones');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search): void {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('especialidad')) {
            $query->whereHas('especialidades', fn ($q) => $q->where('especialidades.id', $request->input('especialidad'))
            );
        }

        if ($request->filled('provincia')) {
            $query->where('provincia_id', $request->input('provincia'));
        }

        $order = $request->input('orden', 'desc') === 'asc' ? 'ASC' : 'DESC';
        $ofertas = $query->orderBy('created_at', $order)->paginate(12)->withQueryString();

        $especialidades = Especialidad::where('estado', true)->get();
        $provincias = Provincia::all();

        return view('empresa.borradores.index', compact('ofertas', 'especialidades', 'provincias'));
    }

    public function restaurar(Oferta $oferta): RedirectResponse
    {
        $this->authorize('update', $oferta);

        $oferta->update(['estado' => OfertaEstado::Activa]);

        return redirect()->route('empresa.borradores.index')->with('toast', 'Oferta reactivada y publicada correctamente.');
    }

    public function eliminarDefinitiva(Oferta $oferta): RedirectResponse
    {
        $this->authorize('delete', $oferta);

        $oferta->forceDelete();

        return redirect()->route('empresa.borradores.index')->with('toast', 'Oferta eliminada definitivamente.');
    }
}
