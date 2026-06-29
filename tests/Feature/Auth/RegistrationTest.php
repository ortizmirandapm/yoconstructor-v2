<?php

namespace Tests\Feature\Auth;

use App\Models\Especialidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Especialidad::factory()->create(['id' => 1]);

        $response = $this->post('/register', [
            'tipo' => 'trabajador',
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'dni' => '12345678',
            'especialidad_id' => 1,
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home', absolute: false));
    }
}
