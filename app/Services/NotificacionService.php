<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Trabajador;
use App\Models\User;
use App\Notifications\AlertaSistema;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification;

final readonly class NotificacionService
{
    public function obtenerNotificaciones(User $user, string $filtro): LengthAwarePaginator
    {
        $typeClassMap = [
            'oferta' => 'NuevaOfertaMatch',
            'postulacion' => 'PostulacionActualizada',
            'sistema' => 'AlertaSistema',
        ];

        $query = $user->notifications();
        if ($filtro !== 'todas' && isset($typeClassMap[$filtro])) {
            $query->where('type', 'like', '%' . $typeClassMap[$filtro]);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function obtenerConteos(User $user): array
    {
        $all = $user->notifications()->get(['id', 'type', 'read_at']);
        $counts = [
            'todas' => ['total' => 0, 'no_leidas' => 0],
            'postulacion' => ['total' => 0, 'no_leidas' => 0],
            'oferta' => ['total' => 0, 'no_leidas' => 0],
            'sistema' => ['total' => 0, 'no_leidas' => 0],
        ];

        foreach ($all as $n) {
            $basename = class_basename($n->type);
            $key = match ($basename) {
                'NuevaOfertaMatch' => 'oferta',
                'PostulacionActualizada' => 'postulacion',
                'AlertaSistema' => 'sistema',
                default => null,
            };
            $counts['todas']['total']++;
            if (is_null($n->read_at)) {
                $counts['todas']['no_leidas']++;
            }
            if ($key) {
                $counts[$key]['total']++;
                if (is_null($n->read_at)) {
                    $counts[$key]['no_leidas']++;
                }
            }
        }

        return $counts;
    }

    public function chequearPerfilIncompleto(User $user, ?Trabajador $trabajador): void
    {
        if (!$trabajador) {
            return;
        }

        $faltantes = [];

        if (empty($trabajador->descripcion)) $faltantes[] = 'descripción profesional';
        if (empty($trabajador->telefono)) $faltantes[] = 'teléfono de contacto';
        if (empty($trabajador->imagen_perfil)) $faltantes[] = 'foto de perfil';
        if (empty($trabajador->curriculum_pdf)) $faltantes[] = 'curriculum vitae';
        if ($trabajador->especialidades()->count() === 0) $faltantes[] = 'especialidades';
        if (empty($trabajador->provincia_preferencia_id)) $faltantes[] = 'provincia de preferencia';

        if (empty($faltantes)) {
            return;
        }

        $titulo = 'Completá tu perfil para recibir mejores ofertas';
        $mensaje = 'Faltan completar: ' . implode(', ', $faltantes) . '.';
        $url = route('trabajador.perfil.edit');

        $alreadyExists = $user->notifications()
            ->where('type', 'App\Notifications\AlertaSistema')
            ->where('data->titulo', $titulo)
            ->whereNull('read_at')
            ->exists();

        if (!$alreadyExists) {
            $user->notify(new AlertaSistema($titulo, $mensaje, $url));
        }
    }

    public function marcarLeida(User $user, string $id): void
    {
        $user->notifications()->where('id', $id)->first()?->markAsRead();
    }

    public function marcarTodasLeidas(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }
}
