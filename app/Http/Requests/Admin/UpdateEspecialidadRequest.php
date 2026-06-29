<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateEspecialidadRequest extends FormRequest
{
    public function rules(): array
    {
        $especialidadId = $this->route('especialidad');

        return [
            'nombre' => ['required', 'string', 'max:100', 'unique:especialidades,nombre,'.$especialidadId],
            'descripcion' => ['nullable', 'string'],
        ];
    }
}
