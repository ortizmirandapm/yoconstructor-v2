<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class ArchivoService
{
    private const PERFIL_PATH = 'uploads/perfil';
    private const CV_PATH = 'uploads/cv';

    public function subirImagenPerfil(UploadedFile $file, int $trabajadorId): string
    {
        $filename = 'perfil_' . $trabajadorId . '_' . time() . '.' . $file->extension();
        $file->move(public_path(self::PERFIL_PATH), $filename);
        return $filename;
    }

    public function eliminarImagenPerfil(string $filename): void
    {
        $path = public_path(self::PERFIL_PATH . '/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function subirCurriculum(UploadedFile $file, int $trabajadorId): string
    {
        $filename = 'cv_' . $trabajadorId . '_' . time() . '.pdf';
        $file->move(public_path(self::CV_PATH), $filename);
        return $filename;
    }

    public function eliminarCurriculum(string $filename): void
    {
        $path = public_path(self::CV_PATH . '/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
