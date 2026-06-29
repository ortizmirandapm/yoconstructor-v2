<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class AdminEmpresaService
{
    public function crear(array $data): void
    {
        DB::transaction(function () use ($data): void {
            $user = User::create([
                'name' => $data['nombre'],
                'email' => $data['email'],
                'password' => $data['password'],
                'tipo' => 'empresa',
                'estado' => true,
            ]);

            Empresa::create([
                'user_id' => $user->id,
                'nombre_empresa' => $data['razon_social'],
                'razon_social' => $data['razon_social'],
                'rubro_id' => $data['rubro_id'],
                'cuit' => $data['cuit'],
                'email_contacto' => $data['email_contacto'],
                'telefono' => $data['telefono'],
                'estado' => 'activo',
            ]);
        });
    }

    public function actualizar(Empresa $empresa, array $data, ?string $password): void
    {
        DB::transaction(function () use ($empresa, $data, $password): void {
            $userData = [
                'name' => $data['nombre'],
                'email' => $data['email'],
            ];

            if ($password) {
                $userData['password'] = $password;
            }

            $empresa->user->update($userData);

            $empresa->update([
                'nombre_empresa' => $data['razon_social'],
                'razon_social' => $data['razon_social'],
                'rubro_id' => $data['rubro_id'],
                'cuit' => $data['cuit'],
                'email_contacto' => $data['email_contacto'],
                'telefono' => $data['telefono'],
                'domicilio' => $data['domicilio'] ?? $empresa->domicilio,
            ]);
        });
    }

    public function cambiarEstado(Empresa $empresa): void
    {
        $nuevoEstado = $empresa->estado === 'activo' ? 'inactivo' : 'activo';

        DB::transaction(function () use ($empresa, $nuevoEstado): void {
            $empresa->update(['estado' => $nuevoEstado]);

            if ($nuevoEstado === 'inactivo') {
                $empresa->ofertas()->where('estado', 'Activa')->update(['estado' => 'Pausada']);
            }

            $empresa->user->update(['estado' => $nuevoEstado === 'activo']);
        });
    }

    public function eliminar(Empresa $empresa): void
    {
        DB::transaction(function () use ($empresa): void {
            $ofertaIds = $empresa->ofertas()->pluck('id');

            DB::table('postulaciones')->whereIn('oferta_id', $ofertaIds)->delete();
            $empresa->ofertas()->delete();
            DB::table('reclutadores')->where('empresa_id', $empresa->id)->delete();
            $empresa->user()->delete();
            $empresa->delete();
        });
    }
}
