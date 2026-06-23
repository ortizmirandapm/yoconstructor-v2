<?php

declare(strict_types=1);

namespace Tests\Feature\Trabajador;

use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PerfilControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['tipo' => 'trabajador']);
        Trabajador::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_trabajador_can_view_perfil(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('trabajador.perfil.edit'));

        $response->assertOk();
    }

    public function test_trabajador_can_update_perfil(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('trabajador.perfil.update'), [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'telefono' => '123456789',
            ]);

        $response->assertRedirect(route('trabajador.perfil.edit'));
        $this->assertDatabaseHas('trabajadores', [
            'user_id' => $this->user->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
        ]);
    }
}
