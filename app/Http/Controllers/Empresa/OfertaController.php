<?php

declare(strict_types=1);

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfertaStoreRequest;
use App\Http\Requests\OfertaUpdateRequest;
use App\Models\Especialidad;
use App\Models\Oferta;
use App\Models\Provincia;
use App\Models\Rubro;
use App\Services\OfertaService;
use Illuminate\Http\RedirectResponse;
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

    public function index(): View
    {
        $ofertas = $this->getEmpresa()->ofertas()->latest()->paginate(10);
        return view('empresa.ofertas.index', compact('ofertas'));
    }

    public function create(): View
    {
        $especialidades = Especialidad::where('estado', true)->get();
        $rubros = Rubro::where('estado', true)->get();
        $provincias = Provincia::all();

        return view('empresa.ofertas.create', compact('especialidades', 'rubros', 'provincias'));
    }

    public function store(OfertaStoreRequest $request): RedirectResponse
    {
        $data = $request->except('especialidades', 'especialidad_principal');

        $this->ofertaService->crear(
            $data,
            $this->getEmpresa(),
            $request->input('especialidades', []),
            $request->input('especialidad_principal'),
        );

        return redirect()->route('empresa.ofertas.index')->with('success', 'Oferta publicada.');
    }

    public function show(string $id): void
    {
        //
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
            $request->input('especialidad_principal'),
        );

        return redirect()->route('empresa.ofertas.index')->with('success', 'Oferta actualizada.');
    }

    public function destroy(Oferta $oferta): RedirectResponse
    {
        $this->authorize('delete', $oferta);

        $oferta->delete();

        return redirect()->route('empresa.ofertas.index')->with('success', 'Oferta eliminada.');
    }
}
