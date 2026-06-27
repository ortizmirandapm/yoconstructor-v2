<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdministradorControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['tipo' => 'admin']);
    }

    public function test_admin_can_list_administradores(): void
    {
        User::factory()->count(3)->create(['tipo' => 'admin']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.administradores.index'));

        $response->assertOk();
        $response->assertViewHas('administradores');
    }

    public function test_admin_can_create_administrador(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.administradores.store'), [
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertRedirect(route('admin.administradores.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'admin@test.com',
            'tipo' => 'admin',
            'name' => 'Admin Test',
        ]);
    }

    public function test_admin_can_edit_administrador(): void
    {
        $otro = User::factory()->create(['tipo' => 'admin']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.administradores.edit', $otro));

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $otro->id,
            'email' => $otro->email,
        ]);
    }

    public function test_admin_can_update_administrador(): void
    {
        $otro = User::factory()->create(['tipo' => 'admin']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.administradores.update', $otro), [
                'name' => 'Nuevo Nombre',
                'email' => $otro->email,
            ]);

        $response->assertRedirect(route('admin.administradores.index'));
        $this->assertDatabaseHas('users', [
            'id' => $otro->id,
            'name' => 'Nuevo Nombre',
        ]);
    }

    public function test_admin_can_toggle_administrador_estado(): void
    {
        $otro = User::factory()->create(['tipo' => 'admin', 'estado' => true]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.administradores.estado', $otro));

        $response->assertRedirect(route('admin.administradores.index'));
        $this->assertFalse($otro->fresh()->estado);
    }

    public function test_admin_cannot_toggle_self(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.administradores.estado', $this->admin));

        $response->assertRedirect(route('admin.administradores.index'));
        $response->assertSessionHas('error');
        $this->assertTrue($this->admin->fresh()->estado);
    }

    public function test_admin_can_delete_administrador(): void
    {
        $otro = User::factory()->create(['tipo' => 'admin']);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.administradores.destroy', $otro));

        $response->assertRedirect(route('admin.administradores.index'));
        $this->assertDatabaseMissing('users', ['id' => $otro->id]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.administradores.destroy', $this->admin));

        $response->assertRedirect(route('admin.administradores.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_validation_on_create(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.administradores.store'), [
                'name' => '',
                'email' => 'invalid',
                'password' => 'short',
                'password_confirmation' => 'not-matching',
            ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_non_admin_cannot_access(): void
    {
        $user = User::factory()->create(['tipo' => 'trabajador']);

        $response = $this->actingAs($user)
            ->get(route('admin.administradores.index'));

        $response->assertForbidden();
    }
}
