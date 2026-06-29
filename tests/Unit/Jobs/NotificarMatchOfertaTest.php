<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\NotificarMatchOferta;
use App\Models\Especialidad;
use App\Models\Oferta;
use App\Models\Trabajador;
use App\Models\User;
use App\Notifications\NuevaOfertaMatch;
use App\Services\OfertaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class NotificarMatchOfertaTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_dispatches_notifications_to_matching_trabajadores(): void
    {
        Notification::fake();

        $especialidad = Especialidad::factory()->create(['nombre' => 'Laravel']);
        $trabajadorUser = User::factory()->create(['tipo' => 'trabajador']);
        $trabajador = Trabajador::factory()->create(['user_id' => $trabajadorUser->id]);
        $trabajador->especialidades()->attach($especialidad->id, [
            'nivel_experiencia' => 'Intermedio',
            'es_principal' => true,
        ]);

        $oferta = Oferta::factory()->activa()->create();
        $oferta->especialidades()->attach($especialidad->id, ['es_principal' => true]);

        (new NotificarMatchOferta($oferta))->handle(app(OfertaService::class));

        Notification::assertSentTo(
            $trabajadorUser,
            NuevaOfertaMatch::class,
            fn ($notification) => $notification->oferta->id === $oferta->id,
        );
    }

    public function test_job_does_nothing_when_no_matching_trabajadores(): void
    {
        Notification::fake();

        $especialidad = Especialidad::factory()->create(['nombre' => 'Laravel']);
        $oferta = Oferta::factory()->activa()->create();
        $oferta->especialidades()->attach($especialidad->id, ['es_principal' => true]);

        (new NotificarMatchOferta($oferta))->handle(app(OfertaService::class));

        Notification::assertNothingSent();
    }

    public function test_job_does_nothing_when_oferta_has_no_especialidades(): void
    {
        Notification::fake();

        $oferta = Oferta::factory()->activa()->create();

        (new NotificarMatchOferta($oferta))->handle(app(OfertaService::class));

        Notification::assertNothingSent();
    }

    public function test_job_has_retry_config(): void
    {
        $job = new NotificarMatchOferta(Oferta::factory()->make());

        $this->assertEquals(3, $job->tries);
        $this->assertEquals(60, $job->timeout);
    }
}
