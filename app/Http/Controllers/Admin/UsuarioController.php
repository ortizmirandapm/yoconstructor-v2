<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::latest()->paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function cambiarEstado(User $user)
    {
        $user->update(['estado' => !$user->estado]);
        return back()->with('success', 'Estado actualizado.');
    }
}