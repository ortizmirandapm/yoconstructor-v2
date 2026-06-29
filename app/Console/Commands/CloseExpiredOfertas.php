<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\OfertaEstado;
use App\Models\Oferta;
use Illuminate\Console\Command;

final class CloseExpiredOfertas extends Command
{
    protected $signature = 'ofertas:close-expired';

    protected $description = 'Cierra ofertas activas cuya fecha de vencimiento ya pasó';

    public function handle(): int
    {
        $count = Oferta::where('estado', OfertaEstado::Activa->value)
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', now())
            ->update(['estado' => OfertaEstado::Cerrada->value]);

        $this->info("{$count} ofertas cerradas por vencimiento.");

        return self::SUCCESS;
    }
}
