<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OfertaEstado;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\Trabajador;
use App\Notifications\NuevaOfertaMatch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class OfertaService
{
    public function crear(array $data, Empresa $empresa, array $especialidades, ?int $especialidadPrincipal): Oferta
    {
        return DB::transaction(function () use ($data, $empresa, $especialidades, $especialidadPrincipal): Oferta {
            $oferta = $empresa->ofertas()->create($data);

            $this->syncEspecialidades($oferta, $especialidades, $especialidadPrincipal);

            return $oferta;
        });
    }

    public function actualizar(Oferta $oferta, array $data, array $especialidades, ?int $especialidadPrincipal): Oferta
    {
        return DB::transaction(function () use ($oferta, $data, $especialidades, $especialidadPrincipal): Oferta {
            $oferta->update($data);

            $this->syncEspecialidades($oferta, $especialidades, $especialidadPrincipal);

            return $oferta;
        });
    }

    public function syncEspecialidades(Oferta $oferta, array $especialidadIds, ?int $principalId): void
    {
        $sync = [];
        foreach ($especialidadIds as $id) {
            $sync[$id] = ['es_principal' => $principalId === (int) $id ? 1 : 0];
        }
        $oferta->especialidades()->sync($sync);
    }

    public function notificarTrabajadoresMatch(Oferta $oferta): void
    {
        $especialidadIds = $oferta->especialidades()->pluck('especialidades.id');

        if ($especialidadIds->isEmpty()) {
            return;
        }

        $trabajadores = $this->getTrabajadoresConEspecialidades($especialidadIds);

        foreach ($trabajadores as $trabajador) {
            if ($trabajador->user) {
                $trabajador->user->notify(new NuevaOfertaMatch($oferta));
            }
        }
    }

    private function getTrabajadoresConEspecialidades(Collection $especialidadIds): EloquentCollection
    {
        return Trabajador::whereHas('especialidades', function ($query) use ($especialidadIds) {
            $query->whereIn('especialidades.id', $especialidadIds);
        })->with('user')->get();
    }

    public function obtenerOfertasPublicas(array $filters): LengthAwarePaginator
    {
        $query = Oferta::with(['empresa', 'provincia', 'especialidades'])
            ->where('estado', OfertaEstado::Activa)
            ->withCount('postulaciones as total_postulantes');

        if (! empty($filters['rubro'])) {
            $query->where('rubro_id', $filters['rubro']);
        }

        if (! empty($filters['especialidad'])) {
            $query->whereHas('especialidades', fn ($q) => $q->where('especialidades.id', $filters['especialidad'])
            );
        }

        if (! empty($filters['provincia'])) {
            $query->where('provincia_id', $filters['provincia']);
        }

        if (! empty($filters['modalidad'])) {
            $query->where('modalidad', $filters['modalidad']);
        }

        if (! empty($filters['contrato'])) {
            $query->where('tipo_contrato', $filters['contrato']);
        }

        if (! empty($filters['buscar'])) {
            $buscar = $filters['buscar'];
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'like', "%{$buscar}%")
                    ->orWhere('descripcion', 'like', "%{$buscar}%")
                    ->orWhereHas('empresa', fn ($e) => $e->where('nombre_empresa', 'like', "%{$buscar}%"));
            });
        }

        return $query->latest()->paginate(10)->withQueryString();
    }
}
