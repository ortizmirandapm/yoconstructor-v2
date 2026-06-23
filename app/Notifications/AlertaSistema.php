<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AlertaSistema extends Notification
{
    public function __construct(
        public string $titulo,
        public string $mensaje,
        public ?string $url = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'titulo'  => $this->titulo,
            'mensaje' => $this->mensaje,
            'url'     => $this->url,
        ];
    }
}
