<?php

declare(strict_types=1);

namespace Tests\Feature\Empresa;

use App\Enums\PostulacionEstado;
use App\Models\Empresa;
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
    private Empresa $empresa;
    private Oferta $oferta;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['tipo' => 'empresa']);
        $this->empresa = Empresa::factory()->create(['user_id' => $this->user->id]);
        $this->oferta = Oferta::factory()->create(['empresa_id' => $this->empresa->id]);
    }

    public function test_empresa_can_change_postulacion_estado(): void
    {
        $postulacion = Postulacion::factory()->create([
            'oferta_id' => $this->oferta->id,
        ]);

        $response = $this->actingAs($this->user)
            ->patch(route('empresa.postulaciones.estado', $postulacion), [
                'estado' => PostulacionEstado::Entrevista->value,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('postulaciones', [
            'id' => $postulacion->id,
            'estado' => PostulacionEstado::Entrevista->value,
        ]);
    }
}
