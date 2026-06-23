<?php

declare(strict_types=1);

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmpresaPerfilUpdateRequest;
use App\Models\Provincia;
use App\Models\Rubro;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class PerfilController extends Controller
{
    public function edit(): View
    {
        $empresa = auth()->user()->empresa;
        $provincias = Provincia::all();
        $rubros = Rubro::where('estado', true)->get();

        return view('empresa.perfil', compact('empresa', 'provincias', 'rubros'));
    }

    public function update(EmpresaPerfilUpdateRequest $request): RedirectResponse
    {
        $empresa = auth()->user()->empresa;
        $empresa->update($request->validated());

        return redirect()->route('empresa.perfil.edit')->with('success', 'Perfil actualizado.');
    }
}
