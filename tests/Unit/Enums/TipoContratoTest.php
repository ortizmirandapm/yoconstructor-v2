<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\TipoContrato;
use PHPUnit\Framework\TestCase;

final class TipoContratoTest extends TestCase
{
    public function test_values_returns_all_values(): void
    {
        $values = TipoContrato::values();

        $this->assertContains('Tiempo completo', $values);
        $this->assertContains('Medio tiempo', $values);
        $this->assertContains('Por proyecto', $values);
        $this->assertContains('Pasantía', $values);
        $this->assertCount(4, $values);
    }
}
