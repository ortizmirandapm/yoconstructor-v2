<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Provincia;
use App\Models\Rubro;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function edit()
    {
        $empresa   = auth()->user()->empresa;
        $provincias = Provincia::all();
        $rubros     = Rubro::where('estado', true)->get();

        return view('empresa.perfil', compact('empresa', 'provincias', 'rubros'));
    }

    public function update(Request $request)
    {
        $empresa = auth()->user()->empresa;

        $request->validate([
            'nombre_empresa'  => 'required|string|max:200',
            'razon_social'    => 'nullable|string|max:100',
            'cuit'            => 'nullable|string|max:20|unique:empresas,cuit,' . $empresa->id,
            'descripcion'     => 'nullable|string|max:2000',
            'telefono'        => 'nullable|string|max:20',
            'email_contacto'  => 'nullable|email|max:100',
            'domicilio'       => 'nullable|string|max:100',
            'provincia_id'    => 'nullable|exists:provincias,id',
            'rubro_id'        => 'nullable|exists:rubros,id',
        ]);

        $empresa->update($request->all());

        return redirect()->route('empresa.perfil.edit')->with('success', 'Perfil actualizado.');
    }
}