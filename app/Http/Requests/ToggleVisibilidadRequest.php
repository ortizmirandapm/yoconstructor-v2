<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ToggleVisibilidadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'visible_busqueda' => ['required', 'in:0,1'],
        ];
    }
}
