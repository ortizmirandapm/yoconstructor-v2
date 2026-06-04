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
            ->where('estado', 'Activa');

        // Filtros
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

        if (request('buscar')) {
            $query->where('titulo', 'like', '%' . request('buscar') . '%');
        }

        $ofertas        = $query->latest()->paginate(10)->withQueryString();
        $especialidades = Especialidad::where('estado', true)->get();
        $provincias     = Provincia::all();

        return view('ofertas.index', compact('ofertas', 'especialidades', 'provincias'));
    }

    public function show(Oferta $oferta)
    {
        if ($oferta->estado !== 'Activa') {
            abort(404);
        }

        $oferta->increment('visitas');
        $oferta->load(['empresa', 'especialidades', 'provincia']);

        // Verificar si el trabajador ya se postuló
        $yaPostulado = false;
        if (auth()->check() && auth()->user()->tipo === 'trabajador') {
            $yaPostulado = $oferta->postulaciones()
                ->where('trabajador_id', auth()->user()->trabajador->id)
                ->exists();
        }

        return view('ofertas.show', compact('oferta', 'yaPostulado'));
    }
}