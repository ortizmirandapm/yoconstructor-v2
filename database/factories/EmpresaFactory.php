<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nombre_empresa' => fake()->company(),
            'razon_social' => fake()->company(),
            'cuit' => fake()->unique()->numerify('##-########-#'),
            'telefono' => fake()->phoneNumber(),
            'email_contacto' => fake()->companyEmail(),
            'domicilio' => fake()->address(),
            'estado' => 'activo',
        ];
    }
}
