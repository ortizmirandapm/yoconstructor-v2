<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateRubroRequest extends FormRequest
{
    public function rules(): array
    {
        $rubroId = $this->route('rubro');

        return [
            'nombre' => ['required', 'string', 'max:100', 'unique:rubros,nombre,' . $rubroId],
            'descripcion' => ['nullable', 'string'],
        ];
    }
}
