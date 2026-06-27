<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTrabajadorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'],
            'nombre_titulo' => ['nullable', 'string', 'max:50'],
            'dni' => ['nullable', 'string', 'max:20', 'unique:trabajadores,dni'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'provincia_preferencia_id' => ['nullable', 'exists:provincias,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
