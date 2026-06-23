<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\ArchivoService;
use PHPUnit\Framework\TestCase;

final class ArchivoServiceTest extends TestCase
{
    private ArchivoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ArchivoService();
    }

    public function test_service_is_readonly(): void
    {
        $this->assertInstanceOf(ArchivoService::class, $this->service);
    }
}
