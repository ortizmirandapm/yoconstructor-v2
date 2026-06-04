<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Postulacion;
use App\Models\Oferta;
use Illuminate\Http\Request;

class PostulacionController extends Controller
{
    private function getEmpresa()
    {
        return auth()->user()->empresa;
    }

    public function index()
    {
        $ofertas = Oferta::with(['postulaciones.trabajador.user'])
            ->where('empresa_id', $this->getEmpresa()->id)
            ->whereHas('postulaciones')
            ->latest()
            ->get();

        return view('empresa.postulaciones.index', compact('ofertas'));
    }

    public function cambiarEstado(Request $request, Postulacion $postulacion)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,Revisada,Entrevista,Aceptada,Rechazada',
        ]);

        $postulacion->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado actualizado.');
    }
}