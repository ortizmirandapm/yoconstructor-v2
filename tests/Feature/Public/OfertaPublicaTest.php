<?php

declare(strict_types=1);

namespace Tests\Feature\Public;

use App\Models\Oferta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OfertaPublicaTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_list_active_ofertas(): void
    {
        Oferta::factory()->count(3)->activa()->create();

        $response = $this->get(route('ofertas.index'));

        $response->assertOk();
        $response->assertViewHas('ofertas');
    }

    public function test_public_can_view_active_oferta(): void
    {
        $oferta = Oferta::factory()->activa()->create();

        $response = $this->get(route('ofertas.show', $oferta));

        $response->assertOk();
        $response->assertViewHas('oferta');
    }

    public function test_inactive_oferta_returns_404(): void
    {
        $oferta = Oferta::factory()->pausada()->create();

        $response = $this->get(route('ofertas.show', $oferta));

        $response->assertNotFound();
    }
}
