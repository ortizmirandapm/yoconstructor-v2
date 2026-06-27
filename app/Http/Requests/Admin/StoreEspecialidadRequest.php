<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class StoreEspecialidadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100', 'unique:especialidades,nombre'],
            'descripcion' => ['nullable', 'string'],
        ];
    }
}
