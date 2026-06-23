<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Especialidad;
use Illuminate\Database\Eloquent\Factories\Factory;

final class EspecialidadFactory extends Factory
{
    protected $model = Especialidad::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->word(),
            'descripcion' => fake()->sentence(),
            'estado' => true,
        ];
    }
}
