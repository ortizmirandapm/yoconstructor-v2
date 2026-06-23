<?php

declare(strict_types=1);

namespace Tests\Feature\Empresa;

use App\Enums\OfertaEstado;
use App\Models\Empresa;
use App\Models\Especialidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OfertaControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Empresa $empresa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['tipo' => 'empresa']);
        $this->empresa = Empresa::factory()->create(['user_id' => $this->user->id]);
        Especialidad::factory()->create(['id' => 1, 'nombre' => 'Laravel']);
    }

    public function test_empresa_can_view_their_ofertas(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('empresa.ofertas.index'));

        $response->assertOk();
    }

    public function test_empresa_can_view_create_form(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('empresa.ofertas.create'));

        $response->assertOk();
    }

    public function test_empresa_can_create_oferta(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('empresa.ofertas.store'), [
                'titulo' => 'Desarrollador Laravel',
                'descripcion' => 'Buscamos un developer',
                'especialidades' => [1],
                'tipo_contrato' => 'Tiempo completo',
                'modalidad' => 'Remoto',
            ]);

        $response->assertRedirect(route('empresa.ofertas.index'));
        $this->assertDatabaseHas('ofertas', [
            'titulo' => 'Desarrollador Laravel',
            'empresa_id' => $this->empresa->id,
            'estado' => OfertaEstado::Activa->value,
        ]);
    }

    public function test_empresa_can_delete_own_oferta(): void
    {
        $oferta = $this->empresa->ofertas()->create([
            'titulo' => 'Test',
            'descripcion' => 'Desc',
            'estado' => OfertaEstado::Activa->value,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('empresa.ofertas.destroy', $oferta));

        $response->assertRedirect(route('empresa.ofertas.index'));
        $this->assertSoftDeleted('ofertas', ['id' => $oferta->id]);
    }

    public function test_guest_cannot_create_oferta(): void
    {
        $response = $this->post(route('empresa.ofertas.store'), [
            'titulo' => 'Test',
            'descripcion' => 'Desc',
        ]);

        $response->assertRedirect(route('login'));
    }
}
