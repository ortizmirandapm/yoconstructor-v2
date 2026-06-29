<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\OfertaEstado;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\Rubro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OfertaAdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['tipo' => 'admin']);
        Rubro::factory()->create(['id' => 1]);
    }

    public function test_admin_can_list_ofertas(): void
    {
        Oferta::factory()->count(3)->activa()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.ofertas.index'));

        $response->assertOk();
        $response->assertViewHas('ofertas');
    }

    public function test_admin_can_edit_oferta(): void
    {
        $oferta = Oferta::factory()->activa()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.ofertas.edit', $oferta));

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $oferta->id,
        ]);
    }

    public function test_admin_can_update_oferta(): void
    {
        $oferta = Oferta::factory()->activa()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.ofertas.update', $oferta), [
                'titulo' => 'Titulo Actualizado',
                'descripcion' => 'Descripcion actualizada',
                'tipo_contrato' => 'Tiempo completo',
                'modalidad' => 'Remoto',
                'estado' => OfertaEstado::Activa->value,
            ]);

        $response->assertRedirect(route('admin.ofertas.index'));
        $this->assertDatabaseHas('ofertas', [
            'id' => $oferta->id,
            'titulo' => 'Titulo Actualizado',
        ]);
    }

    public function test_admin_can_toggle_oferta_estado(): void
    {
        $oferta = Oferta::factory()->activa()->create();

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.ofertas.estado', $oferta));

        $response->assertRedirect(route('admin.ofertas.index'));
        $this->assertEquals(OfertaEstado::Pausada->value, $oferta->fresh()->estado->value);
    }

    public function test_admin_can_delete_oferta(): void
    {
        $oferta = Oferta::factory()->activa()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.ofertas.destroy', $oferta));

        $response->assertRedirect(route('admin.ofertas.index'));
        $this->assertSoftDeleted($oferta);
    }

    public function test_non_admin_cannot_access(): void
    {
        $user = User::factory()->create(['tipo' => 'trabajador']);

        $response = $this->actingAs($user)
            ->get(route('admin.ofertas.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_filter_by_estado(): void
    {
        Oferta::factory()->activa()->create();
        Oferta::factory()->pausada()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.ofertas.index', ['estado' => OfertaEstado::Pausada->value]));

        $response->assertOk();
        $response->assertViewHas('ofertas');
    }

    public function test_admin_can_search_oferta(): void
    {
        $empresa = Empresa::factory()->create(['nombre_empresa' => 'Buscame SA']);
        Oferta::factory()->activa()->create(['empresa_id' => $empresa->id, 'titulo' => 'Buscame']);
        Oferta::factory()->activa()->create(['titulo' => 'Otra']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.ofertas.index', ['buscar' => 'Buscame']));

        $response->assertOk();
        $response->assertViewHas('ofertas');
    }
}
