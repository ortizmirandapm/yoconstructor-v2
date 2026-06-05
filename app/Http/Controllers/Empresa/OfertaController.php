<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\Especialidad;
use App\Models\Rubro;
use App\Models\Provincia;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    private function getEmpresa()
    {
        return auth()->user()->empresa;
    }

    public function index()
    {
        $ofertas = $this->getEmpresa()->ofertas()->latest()->paginate(10);
        return view('empresa.ofertas.index', compact('ofertas'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $especialidades = Especialidad::where('estado', true)->get();
        $rubros         = Rubro::where('estado', true)->get();
        $provincias     = Provincia::all();
        return view('empresa.ofertas.create', compact('especialidades', 'rubros', 'provincias'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo'          => 'required|string|max:200',
            'descripcion'     => 'required|string',
            'especialidades'  => 'required|array|min:1',
            'tipo_contrato'   => 'required',
            'modalidad'       => 'required',
        ]);

        $oferta = $this->getEmpresa()->ofertas()->create($request->except('especialidades', 'especialidad_principal'));

        // Adjuntar especialidades a la pivot
        $especialidadesSync = [];
        foreach ($request->especialidades as $id) {
            $especialidadesSync[$id] = [
                'es_principal' => $request->especialidad_principal == $id ? 1 : 0
            ];
        }
        $oferta->especialidades()->sync($especialidadesSync);

        // Notificar después del sync
        if ($oferta->estado === 'Activa') {
            $especialidadIds = $oferta->especialidades()->pluck('especialidades.id');

            \App\Models\Trabajador::whereHas('especialidades', function ($query) use ($especialidadIds) {
                $query->whereIn('especialidades.id', $especialidadIds);
            })->with('user')->get()->each(function ($trabajador) use ($oferta) {
                $trabajador->user->notify(new \App\Notifications\NuevaOfertaMatch($oferta));
            });
        }

        return redirect()->route('empresa.ofertas.index')->with('success', 'Oferta publicada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Oferta $oferta)
    {
        if (auth()->user()->empresa?->id !== $oferta->empresa_id) {
            abort(403);
        }
      
        $especialidades = Especialidad::where('estado', true)->get();
        $rubros         = Rubro::where('estado', true)->get();
        $provincias     = Provincia::all();
        return view('empresa.ofertas.edit', compact('oferta', 'especialidades', 'rubros', 'provincias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Oferta $oferta)
    {
        if (auth()->user()->empresa?->id !== $oferta->empresa_id) {
            abort(403);
        }
       
        $request->validate([
            'titulo'         => 'required|string|max:200',
            'descripcion'    => 'required|string',
            'especialidades' => 'required|array|min:1',
        ]);

        $oferta->update($request->except('especialidades', 'especialidad_principal'));

        $especialidadesSync = [];
        foreach ($request->especialidades as $id) {
            $especialidadesSync[$id] = [
                'es_principal' => $request->especialidad_principal == $id ? 1 : 0
            ];
        }
        $oferta->especialidades()->sync($especialidadesSync);

        return redirect()->route('empresa.ofertas.index')->with('success', 'Oferta actualizada.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Oferta $oferta)
    {
        if (auth()->user()->empresa?->id !== $oferta->empresa_id) {
            abort(403);
        }
        
        $oferta->delete();
        return redirect()->route('empresa.ofertas.index')->with('success', 'Oferta eliminada.');
    }
}
