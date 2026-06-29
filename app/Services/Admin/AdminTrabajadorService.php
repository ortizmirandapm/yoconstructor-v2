<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class AdminTrabajadorService
{
    public function crear(array $data): void
    {
        DB::transaction(function () use ($data): void {
            $user = User::create([
                'name' => trim($data['nombre'].' '.$data['apellido']),
                'email' => $data['email'],
                'password' => $data['password'],
                'tipo' => 'trabajador',
                'estado' => true,
            ]);

            Trabajador::create([
                'user_id' => $user->id,
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'nombre_titulo' => $data['nombre_titulo'] ?? null,
                'dni' => $data['dni'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'provincia_preferencia_id' => $data['provincia_preferencia_id'] ?? null,
            ]);
        });
    }

    public function actualizar(Trabajador $trabajador, array $data, ?string $password): void
    {
        DB::transaction(function () use ($trabajador, $data, $password): void {
            $trabajador->update([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'nombre_titulo' => $data['nombre_titulo'] ?? null,
                'dni' => $data['dni'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'provincia_preferencia_id' => $data['provincia_preferencia_id'] ?? null,
            ]);

            $userData = [
                'name' => trim($data['nombre'].' '.$data['apellido']),
                'email' => $data['email'],
            ];

            if ($password) {
                $userData['password'] = $password;
            }

            $trabajador->user->update($userData);
        });
    }

    public function cambiarEstado(Trabajador $trabajador): void
    {
        $user = $trabajador->user;
        $user->update(['estado' => ! $user->estado]);
    }

    public function eliminar(Trabajador $trabajador): void
    {
        DB::transaction(function () use ($trabajador): void {
            DB::table('postulaciones')->where('trabajador_id', $trabajador->id)->delete();
            DB::table('notifications')
                ->where('notifiable_id', $trabajador->user_id)
                ->where('notifiable_type', User::class)
                ->delete();
            $trabajador->user()->delete();
        });
    }
}
