<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OfertaEstado;
use App\Enums\PostulacionEstado;
use App\Models\Oferta;
use App\Models\Postulacion;
use App\Models\Trabajador;
use App\Notifications\PostulacionActualizada;

final readonly class PostulacionService
{
    public function crear(Oferta $oferta, Trabajador $trabajador, ?string $mensaje): Postulacion
    {
        return Postulacion::create([
            'oferta_id' => $oferta->id,
            'trabajador_id' => $trabajador->id,
            'mensaje' => $mensaje,
            'estado' => PostulacionEstado::Pendiente,
        ]);
    }

    public function cambiarEstado(Postulacion $postulacion, PostulacionEstado $nuevoEstado): void
    {
        $postulacion->update(['estado' => $nuevoEstado]);

        if ($nuevoEstado->notificable()) {
            $user = $postulacion->trabajador->user;
            if ($user) {
                $user->notify(new PostulacionActualizada($postulacion, $nuevoEstado->value));
            }
        }
    }

    public function cancelar(Postulacion $postulacion, Trabajador $trabajador): bool
    {
        if ($postulacion->trabajador_id !== $trabajador->id) {
            return false;
        }

        if ($postulacion->estado !== PostulacionEstado::Pendiente) {
            return false;
        }

        $postulacion->delete();
        return true;
    }

    public function yaPostulado(Oferta $oferta, Trabajador $trabajador): bool
    {
        return Postulacion::where('oferta_id', $oferta->id)
            ->where('trabajador_id', $trabajador->id)
            ->exists();
    }

    public function obtenerPostulacionesTrabajador(Trabajador $trabajador, ?string $filtroEstado)
    {
        $estadosValidos = PostulacionEstado::cases();
        $estadosLabels = array_map(fn(PostulacionEstado $e) => $e->value, $estadosValidos);

        $query = Postulacion::with(['oferta.empresa', 'oferta.provincia', 'oferta.localidad'])
            ->where('trabajador_id', $trabajador->id)
            ->whereHas('oferta');

        if ($filtroEstado && in_array($filtroEstado, $estadosLabels, true)) {
            $query->where('estado', $filtroEstado);
        }

        $postulaciones = $query->latest()->paginate(10);

        $counts = Postulacion::selectRaw('estado, COUNT(*) as total')
            ->where('trabajador_id', $trabajador->id)
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        return [$postulaciones, $filtroEstado, $estadosLabels, $counts];
    }
}
