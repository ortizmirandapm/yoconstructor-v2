<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Localidad;
use App\Models\Provincia;
use Illuminate\Database\Eloquent\Factories\Factory;

final class LocalidadFactory extends Factory
{
    protected $model = Localidad::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->city(),
            'provincia_id' => Provincia::factory(),
            'codigo_postal' => fake()->optional()->postcode(),
        ];
    }
}
