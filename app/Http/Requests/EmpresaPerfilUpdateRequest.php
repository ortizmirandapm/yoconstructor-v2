<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Empresa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class EmpresaPerfilUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $empresaId = $this->user()?->empresa?->id;

        return [
            'nombre_empresa' => ['required', 'string', 'max:200'],
            'razon_social' => ['nullable', 'string', 'max:100'],
            'cuit' => ['nullable', 'string', 'max:20', Rule::unique(Empresa::class)->ignore($empresaId)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email_contacto' => ['nullable', 'email', 'max:100'],
            'domicilio' => ['nullable', 'string', 'max:100'],
            'provincia_id' => ['nullable', 'exists:provincias,id'],
            'rubro_id' => ['nullable', 'exists:rubros,id'],
        ];
    }
}
