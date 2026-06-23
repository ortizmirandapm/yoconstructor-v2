<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PostulacionEstado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class CambiarEstadoPostulacionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'estado' => ['required', new Enum(PostulacionEstado::class)],
        ];
    }
}
