<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class EmpresaController extends Controller
{
    public function index(): View
    {
        $empresas = Empresa::with('user')->latest()->paginate(15);
        return view('admin.empresas.index', compact('empresas'));
    }

    public function cambiarEstado(Empresa $empresa): RedirectResponse
    {
        $nuevoEstado = $empresa->estado === 'activo' ? 'inactivo' : 'activo';
        $empresa->update(['estado' => $nuevoEstado]);
        return back()->with('success', 'Estado actualizado.');
    }
}
