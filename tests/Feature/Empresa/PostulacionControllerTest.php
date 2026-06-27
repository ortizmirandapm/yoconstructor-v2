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

    public function test_empresa_can_view_postulantes_of_their_oferta(): void
    {
        Postulacion::factory()->create([
            'oferta_id' => $this->oferta->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('empresa.postulaciones.index', ['oferta' => $this->oferta->id]));

        $response->assertOk();
    }

    public function test_empresa_redirected_when_no_oferta_provided(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('empresa.postulaciones.index'));

        $response->assertRedirect(route('empresa.ofertas.index'));
    }

    public function test_empresa_cannot_view_postulantes_of_other_empresa_oferta(): void
    {
        $otherEmpresa = Empresa::factory()->create();
        $otherOferta = Oferta::factory()->create(['empresa_id' => $otherEmpresa->id]);

        $response = $this->actingAs($this->user)
            ->get(route('empresa.postulaciones.index', ['oferta' => $otherOferta->id]));

        $response->assertNotFound();
    }

    public function test_empresa_can_save_notas_for_postulacion(): void
    {
        $postulacion = Postulacion::factory()->create([
            'oferta_id' => $this->oferta->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('empresa.postulaciones.notas', $postulacion), [
                'notas' => 'Buen perfil técnico',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('postulaciones', [
            'id' => $postulacion->id,
            'notas_empresa' => 'Buen perfil técnico',
        ]);
    }

    public function test_empresa_cannot_save_notas_for_other_empresa_postulacion(): void
    {
        $otherEmpresa = Empresa::factory()->create();
        $otherOferta = Oferta::factory()->create(['empresa_id' => $otherEmpresa->id]);
        $postulacion = Postulacion::factory()->create([
            'oferta_id' => $otherOferta->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('empresa.postulaciones.notas', $postulacion), [
                'notas' => 'Intento no autorizado',
            ]);

        $response->assertForbidden();
    }

    public function test_empresa_can_filter_postulantes_by_estado(): void
    {
        Postulacion::factory()->create([
            'oferta_id' => $this->oferta->id,
            'estado' => PostulacionEstado::Pendiente->value,
        ]);
        Postulacion::factory()->create([
            'oferta_id' => $this->oferta->id,
            'estado' => PostulacionEstado::Aceptada->value,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('empresa.postulaciones.index', [
                'oferta' => $this->oferta->id,
                'estado' => 'Pendiente',
            ]));

        $response->assertOk();
    }

    public function test_empresa_can_view_worker_profile_from_postulantes(): void
    {
        $trabajador = Trabajador::factory()->create();
        Postulacion::factory()->create([
            'oferta_id' => $this->oferta->id,
            'trabajador_id' => $trabajador->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('empresa.postulaciones.perfil', [
                'trabajador' => $trabajador->id,
                'oferta' => $this->oferta->id,
            ]));

        $response->assertOk();
    }

    public function test_empresa_cannot_view_worker_profile_without_postulacion(): void
    {
        $trabajador = Trabajador::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('empresa.postulaciones.perfil', [
                'trabajador' => $trabajador->id,
                'oferta' => $this->oferta->id,
            ]));

        $response->assertForbidden();
    }
}
