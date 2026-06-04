<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Models\Especialidad;
use App\Models\Provincia;
use App\Models\Localidad;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function edit()
    {
        $trabajador     = auth()->user()->trabajador;
        $especialidades = Especialidad::where('estado', true)->get();
        $provincias     = Provincia::all();
        $localidades    = $trabajador->provincia_preferencia_id
            ? Localidad::where('provincia_id', $trabajador->provincia_preferencia_id)->get()
            : collect();

        return view('trabajador.perfil', compact('trabajador', 'especialidades', 'provincias', 'localidades'));
    }

    public function update(Request $request)
    {
        $trabajador = auth()->user()->trabajador;

        $request->validate([
            'nombre'              => 'required|string|max:50',
            'apellido'            => 'required|string|max:50',
            'dni'                 => 'nullable|string|max:10|unique:trabajadores,dni,' . $trabajador->id,
            'telefono'            => 'nullable|string|max:20',
            'descripcion'         => 'nullable|string|max:1000',
            'anios_experiencia'   => 'nullable|integer|min:0|max:50',
            'provincia_preferencia_id' => 'nullable|exists:provincias,id',
            'localidad_preferencia_id' => 'nullable|exists:localidades,id',
            'especialidades'      => 'nullable|array',
            'especialidad_principal' => 'nullable|exists:especialidades,id',
        ]);

        $trabajador->update($request->except('especialidades', 'especialidad_principal'));

        // Sincronizar especialidades
        if ($request->has('especialidades')) {
            $sync = [];
            foreach ($request->especialidades as $id => $nivel) {
                $sync[$id] = [
                    'nivel_experiencia' => $nivel,
                    'es_principal'      => $request->especialidad_principal == $id ? 1 : 0,
                ];
            }
            $trabajador->especialidades()->sync($sync);
        } else {
            $trabajador->especialidades()->detach();
        }

        return redirect()->route('trabajador.perfil.edit')->with('success', 'Perfil actualizado.');
    }
}