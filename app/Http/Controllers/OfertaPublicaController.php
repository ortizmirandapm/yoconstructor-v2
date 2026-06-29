<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TipoContrato;
use App\Enums\UserTipo;
use App\Jobs\IncrementarVisitaOferta;
use App\Models\Especialidad;
use App\Models\Oferta;
use App\Models\Provincia;
use App\Models\Rubro;
use App\Services\OfertaService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class OfertaPublicaController extends Controller
{
    public function __construct(
        private readonly OfertaService $ofertaService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['rubro', 'especialidad', 'provincia', 'modalidad', 'contrato', 'buscar']);

        $ofertas = $this->ofertaService->obtenerOfertasPublicas($filters);

        $trabajadorId = null;
        $prefProvincia = null;
        $prefLocalidad = null;

        if (auth()->check() && auth()->user()->tipo === UserTipo::Trabajador) {
            $trabajador = auth()->user()->trabajador;
            $trabajadorId = $trabajador->id;
            $prefProvincia = $trabajador->provincia_preferencia_id;
            $prefLocalidad = $trabajador->localidad_preferencia_id;

            $ofertas->loadCount(['postulaciones as ya_postulado' => fn ($q) => $q->where('trabajador_id', $trabajadorId)]);
        }

        $especialidades = Especialidad::where('estado', true)->orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $rubros = Rubro::where('estado', true)->orderBy('nombre')->get();
        $tiposContrato = TipoContrato::values();

        return view('ofertas.index', compact(
            'ofertas',
            'especialidades',
            'provincias',
            'rubros',
            'tiposContrato',
            'trabajadorId',
            'prefProvincia',
            'prefLocalidad',
        ));
    }

    public function show(Oferta $oferta): View
    {
        if (! $oferta->estado->esActiva()) {
            abort(404);
        }

        IncrementarVisitaOferta::dispatch($oferta);
        $oferta->load(['empresa', 'especialidades', 'provincia', 'localidad']);

        $totalPostulantes = $oferta->postulaciones()->count();

        $yaPostulado = false;
        $trabajador = null;
        $prefProvincia = null;
        $prefLocalidad = null;

        if (auth()->check() && auth()->user()->tipo === UserTipo::Trabajador) {
            $trabajador = auth()->user()->trabajador;
            $yaPostulado = $oferta->postulaciones()
                ->where('trabajador_id', $trabajador->id)
                ->exists();
            $prefProvincia = $trabajador->provincia_preferencia_id;
            $prefLocalidad = $trabajador->localidad_preferencia_id;
        }

        return view('ofertas.show', compact(
            'oferta',
            'yaPostulado',
            'totalPostulantes',
            'trabajador',
            'prefProvincia',
            'prefLocalidad',
        ));
    }
}
