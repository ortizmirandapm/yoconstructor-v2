<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Especialidad;
use App\Models\Provincia;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TrabajadorControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['tipo' => 'admin']);
    }

    public function test_admin_can_list_trabajadores(): void
    {
        Trabajador::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.trabajadores.index'));

        $response->assertOk();
        $response->assertViewHas('trabajadores');
    }

    public function test_admin_can_create_trabajador(): void
    {
        Provincia::factory()->create(['id' => 1, 'nombre' => 'Catamarca']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.trabajadores.store'), [
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'email' => 'juan@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'nombre_titulo' => 'Albañil',
                'dni' => '12345678',
                'telefono' => '3834000000',
                'provincia_preferencia_id' => 1,
            ]);

        $response->assertRedirect(route('admin.trabajadores.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'juan@test.com',
            'tipo' => 'trabajador',
        ]);
        $this->assertDatabaseHas('trabajadores', [
            'dni' => '12345678',
            'nombre' => 'Juan',
            'apellido' => 'Perez',
        ]);
    }

    public function test_admin_can_edit_trabajador(): void
    {
        $trabajador = Trabajador::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.trabajadores.edit', $trabajador));

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $trabajador->id,
        ]);
    }

    public function test_admin_can_update_trabajador(): void
    {
        $trabajador = Trabajador::factory()->create();
        $user = $trabajador->user;

        $response = $this->actingAs($this->admin)
            ->put(route('admin.trabajadores.update', $trabajador), [
                'nombre' => 'Pedro',
                'apellido' => 'Garcia',
                'email' => $user->email,
                'nombre_titulo' => 'Electricista',
                'dni' => $trabajador->dni,
                'telefono' => '3834111111',
            ]);

        $response->assertRedirect(route('admin.trabajadores.index'));
        $this->assertDatabaseHas('trabajadores', [
            'id' => $trabajador->id,
            'nombre' => 'Pedro',
            'apellido' => 'Garcia',
            'nombre_titulo' => 'Electricista',
        ]);
    }

    public function test_admin_can_toggle_trabajador_estado(): void
    {
        $trabajador = Trabajador::factory()->create();
        $user = $trabajador->user;
        $user->update(['estado' => true]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.trabajadores.estado', $trabajador));

        $response->assertRedirect(route('admin.trabajadores.index'));
        $this->assertFalse($user->fresh()->estado);
    }

    public function test_admin_can_delete_trabajador(): void
    {
        $trabajador = Trabajador::factory()->create();
        $userId = $trabajador->user_id;

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.trabajadores.destroy', $trabajador));

        $response->assertRedirect(route('admin.trabajadores.index'));
        $this->assertDatabaseMissing('trabajadores', ['id' => $trabajador->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_validation_on_create(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.trabajadores.store'), [
                'nombre' => '',
                'apellido' => '',
                'email' => 'invalid-email',
                'password' => 'short',
                'password_confirmation' => 'not-matching',
            ]);

        $response->assertSessionHasErrors(['nombre', 'apellido', 'email', 'password']);
    }

    public function test_non_admin_cannot_access(): void
    {
        $user = User::factory()->create(['tipo' => 'trabajador']);

        $response = $this->actingAs($user)
            ->get(route('admin.trabajadores.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_filter_by_estado(): void
    {
        $activo = Trabajador::factory()->create();
        $activo->user->update(['estado' => true]);

        $inactivo = Trabajador::factory()->create();
        $inactivo->user->update(['estado' => false]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.trabajadores.index', ['estado' => 'activos']));

        $response->assertOk();
        $response->assertViewHas('trabajadores');
    }

    public function test_admin_can_filter_by_especialidad(): void
    {
        $esp = Especialidad::factory()->create(['nombre' => 'Laravel']);
        $trabajador = Trabajador::factory()->create();
        $trabajador->especialidades()->attach($esp->id, [
            'nivel_experiencia' => 'Intermedio',
            'es_principal' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.trabajadores.index', ['especialidad' => $esp->id]));

        $response->assertOk();
        $response->assertViewHas('trabajadores');
    }
}
