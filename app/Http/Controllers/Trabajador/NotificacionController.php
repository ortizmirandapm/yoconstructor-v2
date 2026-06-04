<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = auth()->user()->notifications()->paginate(15);
        return view('trabajador.notificaciones', compact('notificaciones'));
    }

    public function marcarLeida(string $id)
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
        return back();
    }

    public function marcarTodasLeidas()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }
}