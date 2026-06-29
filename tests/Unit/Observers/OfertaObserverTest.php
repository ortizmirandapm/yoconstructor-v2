<?php

declare(strict_types=1);

namespace Tests\Unit\Observers;

use App\Enums\OfertaEstado;
use App\Jobs\NotificarMatchOferta;
use App\Models\Especialidad;
use App\Models\Oferta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

final class OfertaObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_active_oferta_dispatches_notificar_match_job(): void
    {
        Bus::fake();

        $especialidad = Especialidad::factory()->create();
        $oferta = Oferta::factory()->activa()->create();
        $oferta->especialidades()->attach($especialidad->id, ['es_principal' => true]);

        Bus::assertDispatched(NotificarMatchOferta::class, fn ($job) => $job->oferta->id === $oferta->id);
    }

    public function test_creates_pausada_oferta_does_not_dispatch_job(): void
    {
        Bus::fake();

        Oferta::factory()->pausada()->create();

        Bus::assertNotDispatched(NotificarMatchOferta::class);
    }

    public function test_creates_borrador_oferta_does_not_dispatch_job(): void
    {
        Bus::fake();

        Oferta::factory()->create(['estado' => OfertaEstado::Borrador->value]);

        Bus::assertNotDispatched(NotificarMatchOferta::class);
    }

    public function test_creates_cerrada_oferta_does_not_dispatch_job(): void
    {
        Bus::fake();

        Oferta::factory()->create(['estado' => OfertaEstado::Cerrada->value]);

        Bus::assertNotDispatched(NotificarMatchOferta::class);
    }
}
