<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Postulacion;
use Illuminate\Notifications\Notification;

final class PostulacionActualizada extends Notification
{
    public function __construct(public Postulacion $postulacion, public string $nuevoEstado) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $titulo = match ($this->nuevoEstado) {
            'Aceptada' => '¡Tu postulación fue aceptada!',
            'Entrevista' => '¡Pasaste a la etapa de entrevista!',
            'Rechazada' => 'Tu postulación no fue seleccionada',
            'Revisada' => 'Tu postulación fue revisada',
            default => 'Estado de postulación actualizado',
        };

        $mensaje = match ($this->nuevoEstado) {
            'Aceptada' => "Felicitaciones, tu postulación para \"{$this->postulacion->oferta->titulo}\" fue aceptada. La empresa se pondrá en contacto pronto.",
            'Entrevista' => "Tu postulación para \"{$this->postulacion->oferta->titulo}\" avanzó a la etapa de entrevista. Revisá tu correo para más información.",
            'Rechazada' => "Tu postulación para \"{$this->postulacion->oferta->titulo}\" no fue seleccionada esta vez. ¡Seguí buscando nuevas oportunidades!",
            'Revisada' => "Tu postulación para \"{$this->postulacion->oferta->titulo}\" fue revisada por la empresa.",
            default => "El estado de tu postulación para \"{$this->postulacion->oferta->titulo}\" cambió a: {$this->nuevoEstado}.",
        };

        return [
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'postulacion_id' => $this->postulacion->id,
            'oferta_titulo' => $this->postulacion->oferta->titulo,
            'nuevo_estado' => $this->nuevoEstado,
            'url' => route('trabajador.postulaciones.index'),
        ];
    }
}
