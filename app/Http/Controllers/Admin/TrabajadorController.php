<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTrabajadorRequest;
use App\Http\Requests\Admin\UpdateTrabajadorRequest;
use App\Models\Especialidad;
use App\Models\Provincia;
use App\Models\Trabajador;
use App\Models\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class TrabajadorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Trabajador::with('user', 'provincia', 'especialidades')
            ->withCount('postulaciones');

        if ($search = $request->get('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere(DB::raw("CONCAT(nombre, ' ', apellido)"), 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->whereHas('user', fn ($u) => $u->where('estado', true));
            } elseif ($request->estado === 'inactivos') {
                $query->whereHas('user', fn ($u) => $u->where('estado', false));
            }
        }

        if ($request->filled('especialidad')) {
            $query->whereHas('especialidades', fn ($q) => $q->where('especialidades.id', $request->especialidad));
        }

        if ($request->boolean('con_cv')) {
            $query->whereNotNull('curriculum_pdf')->where('curriculum_pdf', '!=', '');
        }

        $trabajadores = $query->latest()->paginate(15)->withQueryString();

        $especialidades = Especialidad::where('estado', true)->orderBy('nombre')->get();
        $provincias = Provincia::where('estado', true)->orderBy('nombre')->get();

        $trabajadoresIds = $trabajadores->pluck('id');
        $totalActivos = Trabajador::whereHas('user', fn ($u) => $u->where('estado', true))->count();
        $totalInactivos = Trabajador::whereHas('user', fn ($u) => $u->where('estado', false))->count();

        return view('admin.trabajadores.index', compact(
            'trabajadores', 'especialidades', 'provincias',
            'totalActivos', 'totalInactivos'
        ));
    }

    public function store(StoreTrabajadorRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => trim($validated['nombre'] . ' ' . $validated['apellido']),
                'email' => $validated['email'],
                'password' => $validated['password'],
                'tipo' => 'trabajador',
                'estado' => true,
            ]);

            Trabajador::create([
                'user_id' => $user->id,
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'nombre_titulo' => $validated['nombre_titulo'] ?? null,
                'dni' => $validated['dni'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'provincia_preferencia_id' => $validated['provincia_preferencia_id'] ?? null,
            ]);
        });

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador creado correctamente.');
    }

    public function edit(Trabajador $trabajador): JsonResponse
    {
        $trabajador->load('user');

        return response()->json($trabajador);
    }

    public function update(UpdateTrabajadorRequest $request, Trabajador $trabajador): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $trabajador, $request) {
            $trabajador->update([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'nombre_titulo' => $validated['nombre_titulo'] ?? null,
                'dni' => $validated['dni'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'provincia_preferencia_id' => $validated['provincia_preferencia_id'] ?? null,
            ]);

            $userData = [
                'name' => trim($validated['nombre'] . ' ' . $validated['apellido']),
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $userData['password'] = $request->password;
            }

            $trabajador->user->update($userData);
        });

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador actualizado correctamente.');
    }

    public function cambiarEstado(Trabajador $trabajador): RedirectResponse
    {
        $user = $trabajador->user;
        $user->update(['estado' => !$user->estado]);

        $mensaje = $user->estado
            ? 'Trabajador activado correctamente.'
            : 'Trabajador desactivado correctamente.';

        return redirect()->route('admin.trabajadores.index')->with('success', $mensaje);
    }

    public function destroy(Trabajador $trabajador): RedirectResponse
    {
        DB::transaction(function () use ($trabajador) {
            DB::table('postulaciones')->where('trabajador_id', $trabajador->id)->delete();
            DB::table('notifications')
                ->where('notifiable_id', $trabajador->user_id)
                ->where('notifiable_type', User::class)
                ->delete();
            $trabajador->user()->delete();
        });

        return redirect()->route('admin.trabajadores.index')
            ->with('success', 'Trabajador eliminado correctamente.');
    }
}
