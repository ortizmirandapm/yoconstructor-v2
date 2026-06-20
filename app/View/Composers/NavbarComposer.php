<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavbarComposer
{
    public function compose(View $view): void
    {
        $navbarUser          = null;
        $navbarNombreCompleto = 'Usuario';
        $navbarTipoNombre    = 'Usuario';
        $navbarEmail         = '';
        $navbarFotoPerfil    = asset('img/profile.png');
        $navbarNotisCount    = 0;
        $navbarUltimasNotis  = [];
        $navbarEsTrabajador  = false;

        if (Auth::check()) {
            $user             = Auth::user();
            $navbarUser       = $user;
            $navbarEmail      = $user->email;
            $navbarEsTrabajador = $user->tipo === 'trabajador';

            if ($user->tipo === 'trabajador' && $user->trabajador) {
                $trabajador = $user->trabajador;
                $navbarNombreCompleto = strtoupper(trim(
                    ($trabajador->nombre ?? '') . ' ' . ($trabajador->apellido ?? '')
                )) ?: $user->name;
                $navbarTipoNombre = 'Trabajador';

                if (!empty($trabajador->imagen_perfil)) {
                    $path = 'uploads/perfil/' . $trabajador->imagen_perfil;
                    $navbarFotoPerfil = file_exists(public_path($path))
                        ? asset($path)
                        : asset('img/profile.png');
                }

                $navbarNotisCount   = $user->unreadNotifications->count();
                $latestNotis        = $user->notifications()
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                $navbarUltimasNotis = $latestNotis->map(fn($n) => [
                    'id_notificacion' => $n->id,
                    'tipo'            => $this->mapNotificationType($n->type),
                    'titulo'          => $n->data['titulo'] ?? 'Notificación',
                    'mensaje'         => $this->buildNotiMessage($n),
                    'leida'           => $n->read_at !== null,
                    'url_accion'      => $n->data['url'] ?? route('trabajador.notificaciones.index'),
                    'fecha_creacion'  => $n->created_at,
                ])->toArray();
            } elseif ($user->tipo === 'empresa') {
                $navbarNombreCompleto = $user->empresa?->nombre_empresa ?: $user->name;
                $navbarTipoNombre = 'Empresa';
            } else {
                $navbarNombreCompleto = $user->name;
                $navbarTipoNombre = ucfirst($user->tipo);
            }
        }

        $view->with(compact(
            'navbarUser',
            'navbarNombreCompleto',
            'navbarTipoNombre',
            'navbarEmail',
            'navbarFotoPerfil',
            'navbarNotisCount',
            'navbarUltimasNotis',
            'navbarEsTrabajador',
        ));
    }

    private function mapNotificationType(string $type): string
    {
        return match (class_basename($type)) {
            'NuevaOfertaMatch' => 'oferta',
            default            => 'info',
        };
    }

    private function buildNotiMessage($notification): string
    {
        $data = $notification->data;

        return match (class_basename($notification->type)) {
            'NuevaOfertaMatch' => 'Nueva oferta de ' . ($data['empresa'] ?? '') . ' en ' . ($data['provincia'] ?? ''),
            default            => $data['mensaje'] ?? 'Tienes una nueva notificación.',
        };
    }
}
