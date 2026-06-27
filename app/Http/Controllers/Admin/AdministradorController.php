<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdministradorRequest;
use App\Http\Requests\Admin\UpdateAdministradorRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AdministradorController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('tipo', 'admin');

        if ($search = $request->get('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->where('estado', true);
            } elseif ($request->estado === 'inactivos') {
                $query->where('estado', false);
            }
        }

        $administradores = $query->latest()->paginate(15)->withQueryString();

        $totalActivos = User::where('tipo', 'admin')->where('estado', true)->count();
        $totalInactivos = User::where('tipo', 'admin')->where('estado', false)->count();

        return view('admin.administradores.index', compact(
            'administradores', 'totalActivos', 'totalInactivos'
        ));
    }

    public function store(StoreAdministradorRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'tipo' => 'admin',
            'estado' => true,
        ]);

        return redirect()->route('admin.administradores.index')
            ->with('success', 'Administrador creado correctamente.');
    }

    public function edit(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function update(UpdateAdministradorRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('admin.administradores.index')
            ->with('success', 'Administrador actualizado correctamente.');
    }

    public function cambiarEstado(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.administradores.index')
                ->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $user->update(['estado' => !$user->estado]);

        $mensaje = $user->estado
            ? 'Administrador activado correctamente.'
            : 'Administrador desactivado correctamente.';

        return redirect()->route('admin.administradores.index')->with('success', $mensaje);
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.administradores.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.administradores.index')
            ->with('success', 'Administrador eliminado correctamente.');
    }
}
