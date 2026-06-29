<?php

declare(strict_types=1);

namespace Tests\Feature\Empresa;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PerfilControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Empresa $empresa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['tipo' => 'empresa']);
        $this->empresa = Empresa::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_empresa_can_view_perfil(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('empresa.perfil.edit'));

        $response->assertOk();
    }

    public function test_empresa_can_update_perfil(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('empresa.perfil.update'), [
                'nombre_empresa' => 'Nueva Empresa SA',
                'razon_social' => 'Nueva Empresa SA',
                'descripcion' => 'Descripción actualizada',
                'telefono' => '3834000111',
                'email_contacto' => 'nuevo@contacto.com',
            ]);

        $response->assertRedirect(route('empresa.perfil.edit'));
        $this->assertDatabaseHas('empresas', [
            'id' => $this->empresa->id,
            'nombre_empresa' => 'Nueva Empresa SA',
            'telefono' => '3834000111',
        ]);
    }
}
