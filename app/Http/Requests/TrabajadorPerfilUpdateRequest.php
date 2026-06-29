<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class TrabajadorPerfilUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $trabajadorId = $this->user()?->trabajador?->id;

        return [
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'],
            'dni' => ['nullable', 'string', 'max:10', 'unique:trabajadores,dni,'.$trabajadorId],
            'telefono' => ['nullable', 'string', 'max:20'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'anios_experiencia' => ['nullable', 'integer', 'min:0', 'max:50'],
            'domicilio' => ['nullable', 'string', 'max:100'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'nombre_titulo' => ['nullable', 'string', 'max:50'],
            'provincia_preferencia_id' => ['nullable', 'exists:provincias,id'],
            'localidad_preferencia_id' => ['nullable', 'exists:localidades,id'],
            'especialidades' => ['nullable', 'array'],
            'especialidades.*' => ['exists:especialidades,id'],
            'especialidad_principal' => ['nullable', 'exists:especialidades,id'],
            'imagen_perfil' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'curriculum_pdf' => ['nullable', 'mimes:pdf', 'max:5120'],
        ];
    }
}
