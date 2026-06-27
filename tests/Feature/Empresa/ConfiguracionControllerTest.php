<?php

declare(strict_types=1);

namespace Tests\Feature\Empresa;

use App\Enums\OfertaEstado;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class ConfiguracionControllerTest extends TestCase
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

    public function test_empresa_can_view_configuracion(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('empresa.configuracion.edit'));

        $response->assertOk();
    }

    public function test_empresa_can_change_password(): void
    {
        $newPassword = 'newpassword123';

        $response = $this->actingAs($this->user)
            ->post(route('empresa.configuracion.password'), [
                'current_password' => 'password',
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check($newPassword, $this->user->fresh()->password));
    }

    public function test_empresa_can_toggle_privacidad(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('empresa.configuracion.privacidad'), [
                'perfil_publico' => 0,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('empresas', [
            'id' => $this->empresa->id,
            'perfil_publico' => false,
        ]);
    }

    public function test_empresa_can_deactivate_account(): void
    {
        $oferta = $this->empresa->ofertas()->create([
            'titulo' => 'Test',
            'descripcion' => 'Desc',
            'estado' => OfertaEstado::Activa->value,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('empresa.configuracion.eliminar'), [
                'confirm_text' => 'ELIMINAR',
            ]);

        $response->assertRedirect('/login?cuenta=baja');
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'estado' => false,
        ]);
        $this->assertDatabaseHas('empresas', [
            'id' => $this->empresa->id,
            'estado' => 'inactivo',
        ]);
        $this->assertDatabaseHas('ofertas', [
            'id' => $oferta->id,
            'estado' => OfertaEstado::Cerrada->value,
        ]);
    }

    public function test_empresa_cannot_deactivate_without_confirm(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('empresa.configuracion.eliminar'), [
                'confirm_text' => 'WRONG',
            ]);

        $response->assertSessionHasErrors('confirm_text');
    }
}
