<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\PostulacionEstado;
use App\Models\Oferta;
use App\Models\Postulacion;
use App\Models\Trabajador;
use App\Services\PostulacionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PostulacionServiceTest extends TestCase
{
    use RefreshDatabase;

    private PostulacionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PostulacionService;
    }

    public function test_creates_postulacion(): void
    {
        $oferta = Oferta::factory()->create();
        $trabajador = Trabajador::factory()->create();

        $postulacion = $this->service->crear($oferta, $trabajador, 'Mi mensaje');

        $this->assertDatabaseHas('postulaciones', [
            'id' => $postulacion->id,
            'oferta_id' => $oferta->id,
            'trabajador_id' => $trabajador->id,
            'mensaje' => 'Mi mensaje',
        ]);
        $this->assertSame(PostulacionEstado::Pendiente->value, $postulacion->estado->value);
    }

    public function test_ya_postulado_returns_true_when_exists(): void
    {
        $postulacion = Postulacion::factory()->create();

        $result = $this->service->yaPostulado(
            $postulacion->oferta,
            $postulacion->trabajador,
        );

        $this->assertTrue($result);
    }

    public function test_ya_postulado_returns_false_when_not_exists(): void
    {
        $oferta = Oferta::factory()->create();
        $trabajador = Trabajador::factory()->create();

        $result = $this->service->yaPostulado($oferta, $trabajador);

        $this->assertFalse($result);
    }

    public function test_cancelar_removes_pendiente_postulacion(): void
    {
        $postulacion = Postulacion::factory()->create([
            'estado' => PostulacionEstado::Pendiente->value,
        ]);

        $result = $this->service->cancelar($postulacion, $postulacion->trabajador);

        $this->assertTrue($result);
        $this->assertModelMissing($postulacion);
    }

    public function test_cancelar_fails_for_non_pendiente(): void
    {
        $postulacion = Postulacion::factory()->create([
            'estado' => PostulacionEstado::Aceptada->value,
        ]);

        $result = $this->service->cancelar($postulacion, $postulacion->trabajador);

        $this->assertFalse($result);
        $this->assertModelExists($postulacion);
    }
}
