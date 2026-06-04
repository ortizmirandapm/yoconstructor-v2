<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Trabajador;
use App\Models\Oferta;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_usuarios'   => User::count(),
            'total_empresas'   => Empresa::count(),
            'total_trabajadores' => Trabajador::count(),
            'ofertas_activas'  => Oferta::where('estado', 'Activa')->count(),
            'ofertas_total'    => Oferta::count(),
            'usuarios_nuevos'  => User::whereDate('created_at', today())->count(),
        ];

        $ofertas_recientes = Oferta::with('empresa')
            ->latest()
            ->take(5)
            ->get();

        $usuarios_recientes = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'ofertas_recientes', 'usuarios_recientes'));
    }
}