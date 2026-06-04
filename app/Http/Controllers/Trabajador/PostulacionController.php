<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\Postulacion;
use Illuminate\Http\Request;


class PostulacionController extends Controller
{
    private function getTrabajador()
    {
        return auth()->user()->trabajador;
    }

    public function index()
    {
        $postulaciones = Postulacion::with(['oferta.empresa', 'oferta.provincia'])
            ->where('trabajador_id', $this->getTrabajador()->id)
            ->latest()
            ->paginate(10);

        return view('trabajador.postulaciones.index', compact('postulaciones'));
    }


    public function crear(Oferta $oferta)
    {
        if ($oferta->estado !== 'Activa') {
            abort(404);
        }

        $yaPostulado = Postulacion::where('oferta_id', $oferta->id)
            ->where('trabajador_id', $this->getTrabajador()->id)
            ->exists();

        if ($yaPostulado) {
            return redirect()->route('ofertas.show', $oferta)
                ->with('error', 'Ya te postulaste a esta oferta.');
        }
        
        $oferta->load('empresa');
        return view('trabajador.postulaciones.crear', compact('oferta'));
    }

    /**/
    
    public function store(Request $request, Oferta $oferta)
    {
        $request->validate([
            'mensaje' => 'nullable|string|max:1000',
        ]);

        Postulacion::create([
            'oferta_id'     => $oferta->id,
            'trabajador_id' => $this->getTrabajador()->id,
            'mensaje'       => $request->mensaje,
            'estado'        => 'Pendiente',
        ]);

        return redirect()->route('trabajador.postulaciones.index')
            ->with('success', 'Te postulaste correctamente.');
    }
}
