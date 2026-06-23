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

    public function index(Request $request)
    {
        $trabajador    = $this->getTrabajador();
        $filtroEstado  = $request->query('estado');
        $estadosValidos = ['Pendiente', 'Revisada', 'Entrevista', 'Aceptada', 'Rechazada'];

        $query = Postulacion::with(['oferta.empresa', 'oferta.provincia', 'oferta.localidad'])
            ->where('trabajador_id', $trabajador->id);

        if ($filtroEstado && in_array($filtroEstado, $estadosValidos)) {
            $query->where('estado', $filtroEstado);
        }

        $postulaciones = $query->latest()->paginate(10);

        $counts = Postulacion::selectRaw('estado, COUNT(*) as total')
            ->where('trabajador_id', $trabajador->id)
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        return view('trabajador.postulaciones.index', compact('postulaciones', 'filtroEstado', 'estadosValidos', 'counts'));
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

    public function cancelar(Postulacion $postulacion)
    {
        $trabajador = $this->getTrabajador();

        if ($postulacion->trabajador_id !== $trabajador->id) {
            abort(403);
        }

        if ($postulacion->estado !== 'Pendiente') {
            return redirect()->route('trabajador.postulaciones.index')
                ->with('error', 'Solo podés cancelar postulaciones en estado Pendiente.');
        }

        $postulacion->delete();

        return redirect()->route('trabajador.postulaciones.index')
            ->with('success', 'Postulación cancelada correctamente.');
    }
}
