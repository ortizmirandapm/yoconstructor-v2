<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = User::latest()->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function cambiarEstado(User $user): RedirectResponse
    {
        $user->update(['estado' => ! $user->estado]);

        return back()->with('success', 'Estado actualizado.');
    }
}
