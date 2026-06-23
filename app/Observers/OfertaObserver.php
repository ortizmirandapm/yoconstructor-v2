<?php

namespace App\Observers;

use App\Models\Oferta;
use App\Models\Trabajador;
use App\Notifications\NuevaOfertaMatch;

class OfertaObserver
{
    public function created(Oferta $oferta): void
    {
        $especialidadIds = $oferta->especialidades()->pluck('especialidades.id');

        if ($especialidadIds->isEmpty()) {
            return;
        }

        $trabajadores = Trabajador::where('user_id', '!=', null)
            ->whereHas('especialidades', function ($query) use ($especialidadIds) {
                $query->whereIn('especialidades.id', $especialidadIds);
            })
            ->with('user')
            ->get();

        foreach ($trabajadores as $trabajador) {
            if ($trabajador->user) {
                $trabajador->user->notify(new NuevaOfertaMatch($oferta));
            }
        }
    }
}
