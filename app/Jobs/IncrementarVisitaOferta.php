<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Oferta;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class IncrementarVisitaOferta implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public int $tries = 1;

    public function __construct(
        private readonly Oferta $oferta,
    ) {}

    public function handle(): void
    {
        $this->oferta->increment('visitas');
    }
}
