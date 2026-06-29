<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Empresa;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateEmpresaRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $empresaId = $this->route('empresa') instanceof Empresa
            ? $this->route('empresa')->id
            : $this->route('empresa');

        $userId = $this->route('empresa') instanceof Empresa
            ? $this->route('empresa')->user_id
            : null;

        return [
            'razon_social' => ['required', 'string', 'max:200'],
            'rubro_id' => ['required', 'exists:rubros,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$userId],
            'cuit' => ['nullable', 'string', 'max:20', 'unique:empresas,cuit,'.$empresaId],
            'email_contacto' => ['nullable', 'email', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'domicilio' => ['nullable', 'string', 'max:100'],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
