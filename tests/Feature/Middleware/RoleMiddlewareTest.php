<?php

declare(strict_types=1);

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_trabajador_route_blocks_empresa(): void
    {
        $empresa = User::factory()->create(['tipo' => 'empresa']);

        $response = $this->actingAs($empresa)
            ->get(route('trabajador.perfil.edit'));

        $response->assertForbidden();
    }

    public function test_empresa_route_blocks_trabajador(): void
    {
        $trabajador = User::factory()->create(['tipo' => 'trabajador']);

        $response = $this->actingAs($trabajador)
            ->get(route('empresa.dashboard'));

        $response->assertForbidden();
    }

    public function test_admin_route_blocks_other_roles(): void
    {
        $trabajador = User::factory()->create(['tipo' => 'trabajador']);

        $response = $this->actingAs($trabajador)
            ->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('trabajador.perfil.edit'));

        $response->assertRedirect(route('login'));
    }
}
