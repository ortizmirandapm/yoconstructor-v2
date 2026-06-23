<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

final class CambiarPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', function (string $attribute, mixed $value, \Closure $fail) {
                if (!Hash::check($value, $this->user()?->password)) {
                    $fail('La contraseña actual no es correcta.');
                }
            }],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }
}
