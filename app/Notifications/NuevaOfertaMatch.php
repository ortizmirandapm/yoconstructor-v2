<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Oferta;
use Illuminate\Notifications\Notification;

final class NuevaOfertaMatch extends Notification
{
    public function __construct(public Oferta $oferta) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'oferta_id' => $this->oferta->id,
            'titulo' => $this->oferta->titulo,
            'empresa' => $this->oferta->empresa->nombre_empresa,
            'modalidad' => $this->oferta->modalidad?->value,
            'provincia' => $this->oferta->provincia?->nombre,
            'url' => url('/ofertas/' . $this->oferta->id),
        ];
    }
}
