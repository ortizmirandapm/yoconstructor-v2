<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class PostulacionStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mensaje' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
