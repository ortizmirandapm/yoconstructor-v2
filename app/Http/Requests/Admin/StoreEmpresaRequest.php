<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class StoreEmpresaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'razon_social' => ['required', 'string', 'max:200'],
            'rubro_id' => ['required', 'exists:rubros,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'cuit' => ['nullable', 'string', 'max:20', 'unique:empresas,cuit'],
            'email_contacto' => ['nullable', 'email', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
        ];
    }
}
