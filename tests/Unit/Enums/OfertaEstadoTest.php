<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\OfertaEstado;
use PHPUnit\Framework\TestCase;

final class OfertaEstadoTest extends TestCase
{
    public function test_es_activa_returns_true_for_activa(): void
    {
        $this->assertTrue(OfertaEstado::Activa->esActiva());
    }

    public function test_es_activa_returns_false_for_others(): void
    {
        $this->assertFalse(OfertaEstado::Pausada->esActiva());
        $this->assertFalse(OfertaEstado::Cerrada->esActiva());
        $this->assertFalse(OfertaEstado::Borrador->esActiva());
    }

    public function test_label_returns_correct_value(): void
    {
        $this->assertSame('Activa', OfertaEstado::Activa->label());
        $this->assertSame('Pausada', OfertaEstado::Pausada->label());
        $this->assertSame('Cerrada', OfertaEstado::Cerrada->label());
        $this->assertSame('Borrador', OfertaEstado::Borrador->label());
    }
}
