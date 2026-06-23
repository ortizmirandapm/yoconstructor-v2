<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\OfertaEstado;
use App\Http\Controllers\Controller;
use App\Models\Oferta;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class OfertaAdminController extends Controller
{
    public function index(): View
    {
        $ofertas = Oferta::with('empresa')->latest()->paginate(15);
        return view('admin.ofertas.index', compact('ofertas'));
    }

    public function cambiarEstado(Oferta $oferta): RedirectResponse
    {
        $nuevoEstado = $oferta->estado === OfertaEstado::Activa
            ? OfertaEstado::Pausada
            : OfertaEstado::Activa;

        $oferta->update(['estado' => $nuevoEstado]);

        return back()->with('success', 'Estado actualizado.');
    }
}
