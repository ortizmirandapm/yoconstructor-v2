<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PostulacionEstado;
use App\Models\Oferta;
use App\Models\Postulacion;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Factories\Factory;

final class PostulacionFactory extends Factory
{
    protected $model = Postulacion::class;

    public function definition(): array
    {
        return [
            'oferta_id' => Oferta::factory(),
            'trabajador_id' => Trabajador::factory(),
            'mensaje' => fake()->optional()->paragraph(),
            'estado' => PostulacionEstado::Pendiente->value,
        ];
    }
}
