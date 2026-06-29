<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\Postulacion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class AdminController extends Controller
{
    public function dashboard(): View
    {
        $empresasActivas = Empresa::where('estado', 'activo')->count();
        $empresasInactivas = Empresa::where('estado', 'inactivo')->count();
        $trabajadoresActivos = User::where('tipo', 'trabajador')->where('estado', true)->count();
        $trabajadoresInactivos = User::where('tipo', 'trabajador')->where('estado', false)->count();
        $ofertasActivas = Oferta::where('estado', 'Activa')->count();
        $ofertasBorradores = Oferta::where('estado', 'Borrador')->count();
        $ofertasCerradas = Oferta::where('estado', 'Cerrada')->count();
        $postulacionesTotal = Postulacion::count();
        $postulacionesHoy = Postulacion::whereDate('created_at', today())->count();
        $reportesPendientes = DB::table('reportes')->where('estado', 'pendiente')->count();

        $empresasRecientes = Empresa::with('user')
            ->latest()
            ->take(5)
            ->get();

        $ofertasRecientes = Oferta::with('empresa')
            ->latest()
            ->take(5)
            ->get();

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = today()->subDays($i);
            $chartData[$fecha->toDateString()] = [
                'label' => $fecha->format('d/m'),
                'val' => 0,
            ];
        }

        $postulacionesPorDia = Postulacion::selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->whereDate('created_at', '>=', today()->subDays(6))
            ->groupBy('dia')
            ->pluck('total', 'dia');

        foreach ($postulacionesPorDia as $dia => $total) {
            if (isset($chartData[$dia])) {
                $chartData[$dia]['val'] = (int) $total;
            }
        }

        $chartLabels = array_column(array_values($chartData), 'label');
        $chartValues = array_column(array_values($chartData), 'val');

        return view('admin.dashboard', compact(
            'empresasActivas',
            'empresasInactivas',
            'trabajadoresActivos',
            'trabajadoresInactivos',
            'ofertasActivas',
            'ofertasBorradores',
            'ofertasCerradas',
            'postulacionesTotal',
            'postulacionesHoy',
            'reportesPendientes',
            'empresasRecientes',
            'ofertasRecientes',
            'chartLabels',
            'chartValues',
        ));
    }
}
