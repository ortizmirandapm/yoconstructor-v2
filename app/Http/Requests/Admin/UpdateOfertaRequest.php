<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\Modalidad;
use App\Enums\OfertaEstado;
use App\Enums\TipoContrato;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class UpdateOfertaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:200'],
            'descripcion' => ['required', 'string'],
            'requisitos' => ['nullable', 'string'],
            'salario_min' => ['nullable', 'numeric', 'min:0'],
            'salario_max' => ['nullable', 'numeric', 'min:0', 'gt:salario_min'],
            'tipo_contrato' => ['required', new Enum(TipoContrato::class)],
            'modalidad' => ['required', new Enum(Modalidad::class)],
            'rubro_id' => ['nullable', 'exists:rubros,id'],
            'provincia_id' => ['nullable', 'exists:provincias,id'],
            'localidad_id' => ['nullable', 'exists:localidades,id'],
            'experiencia_requerida' => ['nullable', 'integer', 'min:0', 'max:50'],
            'fecha_vencimiento' => ['nullable', 'date'],
            'estado' => ['required', new Enum(OfertaEstado::class)],
        ];
    }
}
