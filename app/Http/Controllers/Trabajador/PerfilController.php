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
            'nombre'                  => 'required|string|max:50',
            'apellido'                => 'required|string|max:50',
            'dni'                     => 'nullable|string|max:10|unique:trabajadores,dni,' . $trabajador->id,
            'telefono'                => 'nullable|string|max:20',
            'descripcion'             => 'nullable|string|max:500',
            'anios_experiencia'       => 'nullable|integer|min:0|max:50',
            'domicilio'               => 'nullable|string|max:100',
            'fecha_nacimiento'        => 'nullable|date',
            'nombre_titulo'           => 'nullable|string|max:50',
            'provincia_preferencia_id' => 'nullable|exists:provincias,id',
            'localidad_preferencia_id' => 'nullable|exists:localidades,id',
            'especialidades'          => 'nullable|array',
            'especialidad_principal'  => 'nullable|exists:especialidades,id',
            'imagen_perfil'           => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'curriculum_pdf'          => 'nullable|mimes:pdf|max:5120',
        ]);

        $data = $request->except(['especialidades', 'especialidad_principal', 'imagen_perfil', 'curriculum_pdf']);

        if ($request->hasFile('imagen_perfil')) {
            if (!empty($trabajador->imagen_perfil)) {
                $oldPath = public_path('uploads/perfil/' . $trabajador->imagen_perfil);
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $filename = 'perfil_' . $trabajador->id . '_' . time() . '.' . $request->file('imagen_perfil')->extension();
            $request->file('imagen_perfil')->move(public_path('uploads/perfil'), $filename);
            $data['imagen_perfil'] = $filename;
        }

        if ($request->hasFile('curriculum_pdf')) {
            if (!empty($trabajador->curriculum_pdf)) {
                $oldPath = public_path('uploads/cv/' . $trabajador->curriculum_pdf);
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $filename = 'cv_' . $trabajador->id . '_' . time() . '.pdf';
            $request->file('curriculum_pdf')->move(public_path('uploads/cv'), $filename);
            $data['curriculum_pdf'] = $filename;
        }

        $trabajador->update($data);

        if ($request->has('especialidades')) {
            $sync = [];
            foreach ($request->especialidades as $id) {
                $nivel = $request->input('nivel_' . $id, 'Básico');
                $sync[$id] = [
                    'nivel_experiencia' => $nivel,
                    'es_principal'      => $request->especialidad_principal == $id ? 1 : 0,
                ];
            }
            $trabajador->especialidades()->sync($sync);
        } else {
            $trabajador->especialidades()->detach();
        }

        return redirect()->route('trabajador.perfil.edit')->with('success', 'Perfil actualizado correctamente.');
    }
}
