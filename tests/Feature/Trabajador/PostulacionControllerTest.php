<?php

declare(strict_types=1);

namespace Tests\Feature\Trabajador;

use App\Models\Oferta;
use App\Models\Postulacion;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PostulacionControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Trabajador $trabajador;
    private Oferta $oferta;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['tipo' => 'trabajador']);
        $this->trabajador = Trabajador::factory()->create(['user_id' => $this->user->id]);
        $this->oferta = Oferta::factory()->activa()->create();
    }

    public function test_trabajador_can_view_postulaciones(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('trabajador.postulaciones.index'));

        $response->assertOk();
    }

    public function test_trabajador_can_postularse(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('trabajador.postulaciones.store', $this->oferta), [
                'mensaje' => 'Quiero aplicar',
            ]);

        $response->assertRedirect(route('trabajador.postulaciones.index'));
        $this->assertDatabaseHas('postulaciones', [
            'oferta_id' => $this->oferta->id,
            'trabajador_id' => $this->trabajador->id,
        ]);
    }

    public function test_trabajador_can_cancel_pendiente_postulacion(): void
    {
        $postulacion = Postulacion::factory()->create([
            'trabajador_id' => $this->trabajador->id,
            'oferta_id' => $this->oferta->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('trabajador.postulaciones.cancelar', $postulacion));

        $response->assertRedirect(route('trabajador.postulaciones.index'));
        $this->assertModelMissing($postulacion);
    }

    public function test_guest_cannot_postularse(): void
    {
        $response = $this->post(route('trabajador.postulaciones.store', $this->oferta));

        $response->assertRedirect(route('login'));
    }
}
