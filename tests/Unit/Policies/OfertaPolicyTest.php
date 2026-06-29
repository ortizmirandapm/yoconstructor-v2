<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\User;
use App\Policies\OfertaPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OfertaPolicyTest extends TestCase
{
    use RefreshDatabase;

    private OfertaPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new OfertaPolicy;
    }

    public function test_admin_before_returns_true(): void
    {
        $admin = User::factory()->create(['tipo' => 'admin']);

        $result = $this->policy->before($admin);

        $this->assertTrue($result);
    }

    public function test_non_admin_before_returns_null(): void
    {
        $empresaUser = User::factory()->create(['tipo' => 'empresa']);
        $trabajadorUser = User::factory()->create(['tipo' => 'trabajador']);

        $this->assertNull($this->policy->before($empresaUser));
        $this->assertNull($this->policy->before($trabajadorUser));
    }

    public function test_empresa_can_view_own_oferta(): void
    {
        $empresa = Empresa::factory()->create();
        $oferta = Oferta::factory()->create(['empresa_id' => $empresa->id]);

        $result = $this->policy->view($empresa->user, $oferta);

        $this->assertTrue($result);
    }

    public function test_empresa_cannot_view_other_empresa_oferta(): void
    {
        $empresa = Empresa::factory()->create();
        $otherEmpresa = Empresa::factory()->create();
        $oferta = Oferta::factory()->create(['empresa_id' => $otherEmpresa->id]);

        $result = $this->policy->view($empresa->user, $oferta);

        $this->assertFalse($result);
    }

    public function test_trabajador_cannot_view_any_oferta(): void
    {
        $trabajador = User::factory()->create(['tipo' => 'trabajador']);
        $oferta = Oferta::factory()->create();

        $result = $this->policy->view($trabajador, $oferta);

        $this->assertFalse($result);
    }

    public function test_empresa_can_create(): void
    {
        $empresa = Empresa::factory()->create();

        $result = $this->policy->create($empresa->user);

        $this->assertTrue($result);
    }

    public function test_trabajador_cannot_create(): void
    {
        $trabajador = User::factory()->create(['tipo' => 'trabajador']);

        $result = $this->policy->create($trabajador);

        $this->assertFalse($result);
    }

    public function test_admin_cannot_create_without_empresa(): void
    {
        $admin = User::factory()->create(['tipo' => 'admin']);

        $result = $this->policy->create($admin);

        $this->assertFalse($result);
    }

    public function test_empresa_can_update_own_oferta(): void
    {
        $empresa = Empresa::factory()->create();
        $oferta = Oferta::factory()->create(['empresa_id' => $empresa->id]);

        $result = $this->policy->update($empresa->user, $oferta);

        $this->assertTrue($result);
    }

    public function test_empresa_cannot_update_other_empresa_oferta(): void
    {
        $empresa = Empresa::factory()->create();
        $otherEmpresa = Empresa::factory()->create();
        $oferta = Oferta::factory()->create(['empresa_id' => $otherEmpresa->id]);

        $result = $this->policy->update($empresa->user, $oferta);

        $this->assertFalse($result);
    }

    public function test_empresa_can_delete_own_oferta(): void
    {
        $empresa = Empresa::factory()->create();
        $oferta = Oferta::factory()->create(['empresa_id' => $empresa->id]);

        $result = $this->policy->delete($empresa->user, $oferta);

        $this->assertTrue($result);
    }

    public function test_empresa_cannot_delete_other_empresa_oferta(): void
    {
        $empresa = Empresa::factory()->create();
        $otherEmpresa = Empresa::factory()->create();
        $oferta = Oferta::factory()->create(['empresa_id' => $otherEmpresa->id]);

        $result = $this->policy->delete($empresa->user, $oferta);

        $this->assertFalse($result);
    }
}
