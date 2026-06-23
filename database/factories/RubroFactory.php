<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Rubro;
use Illuminate\Database\Eloquent\Factories\Factory;

final class RubroFactory extends Factory
{
    protected $model = Rubro::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->word(),
            'descripcion' => fake()->sentence(),
            'estado' => true,
        ];
    }
}
