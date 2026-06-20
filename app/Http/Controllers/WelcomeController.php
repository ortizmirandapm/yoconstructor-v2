<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Especialidad;
use App\Models\Oferta;
use App\Models\Rubro;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        $statRubros   = Rubro::count();
        $statEsps     = Especialidad::count();
        $statOfertas  = Oferta::where('estado', 'Activa')->count();
        $statEmpresas = Empresa::where('estado', 'activo')->count();

        $rubros = Rubro::where('estado', true)
            ->with([
                'ofertas' => fn($q) => $q->where('estado', 'Activa'),
                'ofertas.especialidades',
            ])
            ->orderBy('orden')
            ->get()
            ->map(function ($rubro) {
                $especialidades = $rubro->ofertas
                    ->flatMap(fn($o) => $o->especialidades)
                    ->unique('id')
                    ->pluck('nombre')
                    ->sort()
                    ->values()
                    ->toArray();

                $rubro->especialidades_list = $especialidades;
                $rubro->ofertas_activas     = $rubro->ofertas->count();
                return $rubro;
            });

        $empresasDestacadas = Empresa::where('estado', 'activo')
            ->with('rubro')
            ->withCount(['ofertas as ofertas_activas' => fn($q) => $q->where('estado', 'Activa')])
            ->orderByDesc('ofertas_activas')
            ->orderBy('nombre_empresa')
            ->take(3)
            ->get();

        return view('welcome', compact(
            'statRubros',
            'statEsps',
            'statOfertas',
            'statEmpresas',
            'rubros',
            'empresasDestacadas',
        ));
    }
}
