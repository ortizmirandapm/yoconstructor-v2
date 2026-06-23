<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\OfertaEstado;
use App\Jobs\NotificarMatchOferta;
use App\Models\Oferta;

final class OfertaObserver
{
    public function created(Oferta $oferta): void
    {
        if ($oferta->estado === OfertaEstado::Activa) {
            NotificarMatchOferta::dispatch($oferta);
        }
    }
}
