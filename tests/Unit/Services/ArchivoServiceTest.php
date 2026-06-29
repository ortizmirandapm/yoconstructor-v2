<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\ArchivoService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

final class ArchivoServiceTest extends TestCase
{
    private ArchivoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ArchivoService;
    }

    public function test_subir_imagen_perfil_moves_file_and_returns_filename(): void
    {
        $file = UploadedFile::fake()->create('foto.jpg', 100);
        $trabajadorId = 42;

        $filename = $this->service->subirImagenPerfil($file, $trabajadorId);

        $this->assertStringStartsWith('perfil_42_', $filename);
        $this->assertStringEndsWith('.jpg', $filename);
        $this->assertFileExists(public_path('uploads/perfil/'.$filename));

        unlink(public_path('uploads/perfil/'.$filename));
    }

    public function test_eliminar_imagen_perfil_removes_file(): void
    {
        $file = UploadedFile::fake()->create('borrar.jpg', 100);
        $filename = $this->service->subirImagenPerfil($file, 99);
        $path = public_path('uploads/perfil/'.$filename);
        $this->assertFileExists($path);

        $this->service->eliminarImagenPerfil($filename);

        $this->assertFileDoesNotExist($path);
    }

    public function test_eliminar_imagen_perfil_does_nothing_if_file_missing(): void
    {
        $this->service->eliminarImagenPerfil('no_existe.jpg');

        $this->assertTrue(true);
    }

    public function test_subir_curriculum_moves_pdf_and_returns_filename(): void
    {
        $file = UploadedFile::fake()->create('cv.pdf', 100);
        $trabajadorId = 7;

        $filename = $this->service->subirCurriculum($file, $trabajadorId);

        $this->assertStringStartsWith('cv_7_', $filename);
        $this->assertStringEndsWith('.pdf', $filename);
        $this->assertFileExists(public_path('uploads/cv/'.$filename));

        unlink(public_path('uploads/cv/'.$filename));
    }

    public function test_eliminar_curriculum_removes_file(): void
    {
        $file = UploadedFile::fake()->create('cv_borrar.pdf', 100);
        $filename = $this->service->subirCurriculum($file, 88);
        $path = public_path('uploads/cv/'.$filename);
        $this->assertFileExists($path);

        $this->service->eliminarCurriculum($filename);

        $this->assertFileDoesNotExist($path);
    }

    public function test_eliminar_curriculum_does_nothing_if_file_missing(): void
    {
        $this->service->eliminarCurriculum('no_existe.pdf');

        $this->assertTrue(true);
    }
}
