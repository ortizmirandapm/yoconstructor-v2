<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Provincia;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProvinciaFactory extends Factory
{
    protected $model = Provincia::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->state(),
            'estado' => true,
        ];
    }
}
