<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Trabajador;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateTrabajadorRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $trabajador = $this->route('trabajador');
        $trabajadorId = $trabajador instanceof Trabajador ? $trabajador->id : $trabajador;
        $userId = $trabajador instanceof Trabajador ? $trabajador->user_id : null;

        return [
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'],
            'nombre_titulo' => ['nullable', 'string', 'max:50'],
            'dni' => ['nullable', 'string', 'max:20', 'unique:trabajadores,dni,'.$trabajadorId],
            'telefono' => ['nullable', 'string', 'max:20'],
            'provincia_preferencia_id' => ['nullable', 'exists:provincias,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$userId],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
