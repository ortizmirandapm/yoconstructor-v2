<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Modalidad;
use App\Enums\TipoContrato;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class OfertaUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:200'],
            'descripcion' => ['required', 'string'],
            'requisitos' => ['nullable', 'string'],
            'salario_min' => ['nullable', 'numeric', 'min:0'],
            'salario_max' => ['nullable', 'numeric', 'min:0'],
            'tipo_contrato' => ['nullable', new Enum(TipoContrato::class)],
            'modalidad' => ['nullable', new Enum(Modalidad::class)],
            'rubro_id' => ['nullable', 'exists:rubros,id'],
            'provincia_id' => ['nullable', 'exists:provincias,id'],
            'localidad_id' => ['nullable', 'exists:localidades,id'],
            'experiencia_requerida' => ['nullable', 'integer', 'min:0', 'max:50'],
            'fecha_vencimiento' => ['nullable', 'date', 'after:today'],
            'especialidades' => ['required', 'array', 'min:1'],
            'especialidades.*' => ['exists:especialidades,id'],
            'especialidad_principal' => ['nullable', 'exists:especialidades,id'],
        ];
    }
}
