<?php

declare(strict_types=1);

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmpresaPerfilUpdateRequest;
use App\Models\Provincia;
use App\Models\Rubro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PerfilController extends Controller
{
    public function edit(): View
    {
        $empresa = auth()->user()->empresa()->with('rubro', 'provincia')->first();
        $provincias = Provincia::all();
        $rubros = Rubro::where('estado', true)->get();

        return view('empresa.perfil', compact('empresa', 'provincias', 'rubros'));
    }

    public function update(EmpresaPerfilUpdateRequest $request): RedirectResponse
    {
        $empresa = auth()->user()->empresa;
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . $empresa->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/logos', $filename, 'public');
            $data['logo'] = $filename;
        }

        $empresa->update($data);

        return redirect()->route('empresa.perfil.edit')
            ->with('toast', 'Perfil actualizado correctamente.')
            ->with('toast_tipo', 'success');
    }
}
