<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Especialidad;
use App\Models\Provincia;
use App\Models\Rubro;

class OfertaPublicaController extends Controller
{
    public function index()
    {
        $query = Oferta::with(['empresa', 'provincia', 'especialidades'])
            ->where('estado', 'Activa')
            ->withCount('postulaciones as total_postulantes');

        if (request('rubro')) {
            $query->where('rubro_id', request('rubro'));
        }

        if (request('especialidad')) {
            $query->whereHas('especialidades', fn($q) =>
                $q->where('especialidades.id', request('especialidad'))
            );
        }

        if (request('provincia')) {
            $query->where('provincia_id', request('provincia'));
        }

        if (request('modalidad')) {
            $query->where('modalidad', request('modalidad'));
        }

        if (request('contrato')) {
            $query->where('tipo_contrato', request('contrato'));
        }

        if (request('buscar')) {
            $buscar = request('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'like', "%$buscar%")
                  ->orWhere('descripcion', 'like', "%$buscar%")
                  ->orWhereHas('empresa', fn($e) => $e->where('nombre_empresa', 'like', "%$buscar%"));
            });
        }

        $trabajadorId = null;
        $prefProvincia = null;
        $prefLocalidad = null;

        if (auth()->check() && auth()->user()->tipo === 'trabajador') {
            $trabajador = auth()->user()->trabajador;
            $trabajadorId = $trabajador->id;
            $prefProvincia = $trabajador->provincia_preferencia_id;
            $prefLocalidad = $trabajador->localidad_preferencia_id;

            $query->withCount(['postulaciones as ya_postulado' => fn($q) => $q->where('trabajador_id', $trabajadorId)]);
        }

        $ofertas = $query->latest()->paginate(10)->withQueryString();

        $especialidades = Especialidad::where('estado', true)->orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $rubros = Rubro::where('estado', true)->orderBy('nombre')->get();
        $tiposContrato = ['Tiempo completo', 'Medio tiempo', 'Por proyecto', 'Pasantía'];

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

    public function show(Oferta $oferta)
    {
        if ($oferta->estado !== 'Activa') {
            abort(404);
        }

        $oferta->increment('visitas');
        $oferta->load(['empresa', 'especialidades', 'provincia', 'localidad']);

        $totalPostulantes = $oferta->postulaciones()->count();

        $yaPostulado = false;
        $trabajador = null;
        $prefProvincia = null;
        $prefLocalidad = null;

        if (auth()->check() && auth()->user()->tipo === 'trabajador') {
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
