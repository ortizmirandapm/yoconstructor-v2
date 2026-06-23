<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['tipo' => 'admin']);
    }

    public function test_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.dashboard'));

        $response->assertOk();
    }

    public function test_non_admin_cannot_view_dashboard(): void
    {
        $user = User::factory()->create(['tipo' => 'trabajador']);

        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertForbidden();
    }
}
