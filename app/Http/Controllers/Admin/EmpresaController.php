<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::with('user')->latest()->paginate(15);
        return view('admin.empresas.index', compact('empresas'));
    }

    public function cambiarEstado(Empresa $empresa)
    {
        $nuevoEstado = $empresa->estado === 'activo' ? 'inactivo' : 'activo';
        $empresa->update(['estado' => $nuevoEstado]);
        return back()->with('success', 'Estado actualizado.');
    }
}