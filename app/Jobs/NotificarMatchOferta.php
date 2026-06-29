<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Oferta;
use App\Services\OfertaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class NotificarMatchOferta implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        public readonly Oferta $oferta,
    ) {}

    public function handle(OfertaService $service): void
    {
        $service->notificarTrabajadoresMatch($this->oferta);
    }

    public function failed(\Throwable $e): void
    {
        logger()->error('NotificarMatchOferta failed', [
            'oferta_id' => $this->oferta->id,
            'error' => $e->getMessage(),
        ]);
    }
}
