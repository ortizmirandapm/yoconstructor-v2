<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\PostulacionEstado;
use PHPUnit\Framework\TestCase;

final class PostulacionEstadoTest extends TestCase
{
    public function test_notificable_returns_true_for_certain_states(): void
    {
        $this->assertTrue(PostulacionEstado::Aceptada->notificable());
        $this->assertTrue(PostulacionEstado::Rechazada->notificable());
        $this->assertTrue(PostulacionEstado::Entrevista->notificable());
        $this->assertTrue(PostulacionEstado::Revisada->notificable());
    }

    public function test_notificable_returns_false_for_pendiente(): void
    {
        $this->assertFalse(PostulacionEstado::Pendiente->notificable());
    }
}
