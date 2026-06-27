<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmpresaRequest;
use App\Http\Requests\Admin\UpdateEmpresaRequest;
use App\Models\Empresa;
use App\Models\Rubro;
use App\Models\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EmpresaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Empresa::with('user', 'rubro')
            ->withCount('ofertas');

        if ($search = $request->get('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_empresa', 'like', "%{$search}%")
                  ->orWhere('razon_social', 'like', "%{$search}%")
                  ->orWhere('cuit', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activas') {
                $query->where('estado', 'activo');
            } elseif ($request->estado === 'inactivas') {
                $query->where('estado', 'inactivo');
            }
        }

        if ($request->filled('rubro')) {
            $query->where('rubro_id', $request->rubro);
        }

        $empresas = $query->latest()->paginate(15)->withQueryString();
        $rubros = Rubro::where('estado', true)->orderBy('nombre')->get();
        $empresaIds = $empresas->pluck('id');

        $postulacionCounts = DB::table('postulaciones')
            ->join('ofertas', 'postulaciones.oferta_id', '=', 'ofertas.id')
            ->whereIn('ofertas.empresa_id', $empresaIds)
            ->groupBy('ofertas.empresa_id')
            ->selectRaw('ofertas.empresa_id, COUNT(*) as total')
            ->pluck('total', 'empresa_id');

        $reclutadoresCounts = DB::table('reclutadores')
            ->whereIn('empresa_id', $empresaIds)
            ->groupBy('empresa_id')
            ->selectRaw('empresa_id, COUNT(*) as total')
            ->pluck('total', 'empresa_id');

        return view('admin.empresas.index', compact(
            'empresas', 'rubros', 'postulacionCounts', 'reclutadoresCounts'
        ));
    }

    public function store(StoreEmpresaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['nombre'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'tipo' => 'empresa',
                'estado' => true,
            ]);

            Empresa::create([
                'user_id' => $user->id,
                'nombre_empresa' => $validated['razon_social'],
                'razon_social' => $validated['razon_social'],
                'rubro_id' => $validated['rubro_id'],
                'cuit' => $validated['cuit'],
                'email_contacto' => $validated['email_contacto'],
                'telefono' => $validated['telefono'],
                'estado' => 'activo',
            ]);
        });

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    public function edit(Empresa $empresa): JsonResponse
    {
        $empresa->load('user');

        return response()->json($empresa);
    }

    public function update(UpdateEmpresaRequest $request, Empresa $empresa): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $empresa, $request) {
            $userData = [
                'name' => $validated['nombre'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $userData['password'] = $request->password;
            }

            $empresa->user->update($userData);

            $empresa->update([
                'nombre_empresa' => $validated['razon_social'],
                'razon_social' => $validated['razon_social'],
                'rubro_id' => $validated['rubro_id'],
                'cuit' => $validated['cuit'],
                'email_contacto' => $validated['email_contacto'],
                'telefono' => $validated['telefono'],
                'domicilio' => $validated['domicilio'] ?? $empresa->domicilio,
            ]);
        });

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function cambiarEstado(Empresa $empresa): RedirectResponse
    {
        $nuevoEstado = $empresa->estado === 'activo' ? 'inactivo' : 'activo';

        DB::transaction(function () use ($empresa, $nuevoEstado) {
            $empresa->update(['estado' => $nuevoEstado]);

            if ($nuevoEstado === 'inactivo') {
                $empresa->ofertas()->where('estado', 'Activa')->update(['estado' => 'Pausada']);
            }

            $empresa->user->update(['estado' => $nuevoEstado === 'activo']);
        });

        $mensaje = $nuevoEstado === 'activo'
            ? 'Empresa activada correctamente.'
            : 'Empresa desactivada correctamente.';

        return redirect()->route('admin.empresas.index')->with('success', $mensaje);
    }

    public function destroy(Empresa $empresa): RedirectResponse
    {
        DB::transaction(function () use ($empresa) {
            $ofertaIds = $empresa->ofertas()->pluck('id');

            DB::table('postulaciones')->whereIn('oferta_id', $ofertaIds)->delete();
            $empresa->ofertas()->delete();
            DB::table('reclutadores')->where('empresa_id', $empresa->id)->delete();
            $empresa->user()->delete();
            $empresa->delete();
        });

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }
}
