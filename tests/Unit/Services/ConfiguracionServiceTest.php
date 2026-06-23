<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\ConfiguracionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class ConfiguracionServiceTest extends TestCase
{
    use RefreshDatabase;

    private ConfiguracionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ConfiguracionService();
    }

    public function test_toggle_visibilidad(): void
    {
        $user = User::factory()->create(['visible_busqueda' => true]);

        $this->service->toggleVisibilidad($user, false);

        $this->assertFalse($user->fresh()->visible_busqueda);
    }

    public function test_cambiar_password(): void
    {
        $user = User::factory()->create();

        $this->service->cambiarPassword($user, 'NewPass123!');

        $this->assertTrue(Hash::check('NewPass123!', $user->fresh()->password));
    }
}
