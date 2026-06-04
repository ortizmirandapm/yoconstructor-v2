<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Oferta;

class OfertaAdminController extends Controller
{
    public function index()
    {
        $ofertas = Oferta::with('empresa')->latest()->paginate(15);
        return view('admin.ofertas.index', compact('ofertas'));
    }

    public function cambiarEstado(Oferta $oferta)
    {
        $nuevoEstado = $oferta->estado === 'Activa' ? 'Pausada' : 'Activa';
        $oferta->update(['estado' => $nuevoEstado]);
        return back()->with('success', 'Estado actualizado.');
    }
}