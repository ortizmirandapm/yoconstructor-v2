<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Empresa;
use App\Models\Rubro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EmpresaControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['tipo' => 'admin']);
    }

    public function test_admin_can_list_empresas(): void
    {
        Empresa::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.empresas.index'));

        $response->assertOk();
        $response->assertViewHas('empresas');
    }

    public function test_admin_can_create_empresa(): void
    {
        $rubro = Rubro::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.empresas.store'), [
                'razon_social' => 'Constructora SA',
                'rubro_id' => $rubro->id,
                'nombre' => 'Juan Perez',
                'email' => 'contacto@constructora.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'cuit' => '30-12345678-9',
                'email_contacto' => 'info@constructora.com',
                'telefono' => '3834000001',
            ]);

        $response->assertRedirect(route('admin.empresas.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'contacto@constructora.com',
            'tipo' => 'empresa',
        ]);
        $this->assertDatabaseHas('empresas', [
            'cuit' => '30-12345678-9',
            'nombre_empresa' => 'Constructora SA',
        ]);
    }

    public function test_admin_can_edit_empresa(): void
    {
        $empresa = Empresa::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.empresas.edit', $empresa));

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $empresa->id,
        ]);
    }

    public function test_admin_can_update_empresa(): void
    {
        $rubro = Rubro::factory()->create();
        $empresa = Empresa::factory()->create();
        $user = $empresa->user;

        $response = $this->actingAs($this->admin)
            ->put(route('admin.empresas.update', $empresa), [
                'razon_social' => 'Nueva Razon Social',
                'rubro_id' => $rubro->id,
                'nombre' => 'Nuevo Nombre',
                'email' => $user->email,
                'cuit' => $empresa->cuit,
                'email_contacto' => 'nuevo@contacto.com',
                'telefono' => '3834000002',
            ]);

        $response->assertRedirect(route('admin.empresas.index'));
        $this->assertDatabaseHas('empresas', [
            'id' => $empresa->id,
            'nombre_empresa' => 'Nueva Razon Social',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nuevo Nombre',
        ]);
    }

    public function test_admin_can_toggle_empresa_estado(): void
    {
        $empresa = Empresa::factory()->create(['estado' => 'activo']);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.empresas.estado', $empresa));

        $response->assertRedirect(route('admin.empresas.index'));
        $this->assertEquals('inactivo', $empresa->fresh()->estado);
    }

    public function test_admin_can_delete_empresa(): void
    {
        $empresa = Empresa::factory()->create();
        $userId = $empresa->user_id;

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.empresas.destroy', $empresa));

        $response->assertRedirect(route('admin.empresas.index'));
        $this->assertDatabaseMissing('empresas', ['id' => $empresa->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_validation_on_create(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.empresas.store'), [
                'razon_social' => '',
                'rubro_id' => null,
                'nombre' => '',
                'email' => 'invalid',
                'password' => 'short',
                'password_confirmation' => 'not-matching',
            ]);

        $response->assertSessionHasErrors(['razon_social', 'rubro_id', 'nombre', 'email', 'password']);
    }

    public function test_non_admin_cannot_access(): void
    {
        $user = User::factory()->create(['tipo' => 'trabajador']);

        $response = $this->actingAs($user)
            ->get(route('admin.empresas.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_filter_by_estado(): void
    {
        Empresa::factory()->create(['estado' => 'activo']);
        Empresa::factory()->create(['estado' => 'inactivo']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.empresas.index', ['estado' => 'activas']));

        $response->assertOk();
        $response->assertViewHas('empresas');
    }

    public function test_admin_can_search_empresa(): void
    {
        Empresa::factory()->create(['nombre_empresa' => 'Buscame SA']);
        Empresa::factory()->create(['nombre_empresa' => 'Otra SA']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.empresas.index', ['buscar' => 'Buscame']));

        $response->assertOk();
        $response->assertViewHas('empresas');
    }
}
