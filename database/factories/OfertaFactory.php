<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Modalidad;
use App\Enums\OfertaEstado;
use App\Enums\TipoContrato;
use App\Models\Empresa;
use App\Models\Oferta;
use Illuminate\Database\Eloquent\Factories\Factory;

final class OfertaFactory extends Factory
{
    protected $model = Oferta::class;

    public function definition(): array
    {
        return [
            'empresa_id' => Empresa::factory(),
            'titulo' => fake()->jobTitle(),
            'descripcion' => fake()->paragraphs(3, true),
            'salario_min' => fake()->randomFloat(2, 300000, 800000),
            'salario_max' => fake()->randomFloat(2, 800000, 2000000),
            'tipo_contrato' => fake()->randomElement(TipoContrato::cases())->value,
            'modalidad' => fake()->randomElement(Modalidad::cases())->value,
            'estado' => OfertaEstado::Activa->value,
        ];
    }

    public function activa(): static
    {
        return $this->state(fn(array $_) => ['estado' => OfertaEstado::Activa->value]);
    }

    public function pausada(): static
    {
        return $this->state(fn(array $_) => ['estado' => OfertaEstado::Pausada->value]);
    }
}
