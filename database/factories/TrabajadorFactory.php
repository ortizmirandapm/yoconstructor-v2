<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TrabajadorFactory extends Factory
{
    protected $model = Trabajador::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'dni' => fake()->unique()->numerify('########'),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'telefono' => fake()->phoneNumber(),
            'anios_experiencia' => fake()->numberBetween(0, 30),
        ];
    }
}
