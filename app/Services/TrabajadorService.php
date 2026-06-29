<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\NivelExperiencia;
use App\Models\Trabajador;
use Illuminate\Http\Request;

final readonly class TrabajadorService
{
    public function __construct(
        private ArchivoService $archivoService,
    ) {}

    public function actualizarPerfil(Trabajador $trabajador, Request $request): Trabajador
    {
        $data = $request->except(['especialidades', 'especialidad_principal', 'imagen_perfil', 'curriculum_pdf']);

        if ($request->hasFile('imagen_perfil')) {
            $data['imagen_perfil'] = $this->handleImagenUpload($trabajador, $request);
        }

        if ($request->hasFile('curriculum_pdf')) {
            $data['curriculum_pdf'] = $this->handleCurriculumUpload($trabajador, $request);
        }

        $trabajador->update($data);

        if ($request->has('especialidades')) {
            $this->syncEspecialidades($trabajador, $request);
        } else {
            $trabajador->especialidades()->detach();
        }

        return $trabajador;
    }

    private function handleImagenUpload(Trabajador $trabajador, Request $request): string
    {
        if (! empty($trabajador->imagen_perfil)) {
            $this->archivoService->eliminarImagenPerfil($trabajador->imagen_perfil);
        }

        return $this->archivoService->subirImagenPerfil(
            $request->file('imagen_perfil'),
            $trabajador->id
        );
    }

    private function handleCurriculumUpload(Trabajador $trabajador, Request $request): string
    {
        if (! empty($trabajador->curriculum_pdf)) {
            $this->archivoService->eliminarCurriculum($trabajador->curriculum_pdf);
        }

        return $this->archivoService->subirCurriculum(
            $request->file('curriculum_pdf'),
            $trabajador->id
        );
    }

    private function syncEspecialidades(Trabajador $trabajador, Request $request): void
    {
        $sync = [];
        foreach ($request->especialidades as $id) {
            $nivel = $request->input('nivel_'.$id, NivelExperiencia::Basico->value);
            $sync[$id] = [
                'nivel_experiencia' => $nivel,
                'es_principal' => $request->especialidad_placement == $id ? 1 : 0,
            ];
        }
        $trabajador->especialidades()->sync($sync);
    }
}
