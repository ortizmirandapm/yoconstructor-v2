<?php

namespace Database\Seeders;

use App\Models\Rubro;
use Illuminate\Database\Seeder;

class RubroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rubros = [
            ['nombre' => 'Construcción General', 'icono' => 'building', 'orden' => 1],
            ['nombre' => 'Electricidad', 'icono' => 'bolt', 'orden' => 2],
            ['nombre' => 'Plomería y Gas', 'icono' => 'wrench', 'orden' => 3],
            ['nombre' => 'Carpintería', 'icono' => 'hammer', 'orden' => 4],
            ['nombre' => 'Pintura y Revestimientos', 'icono' => 'paint-brush', 'orden' => 5],
            ['nombre' => 'Herrería y Soldadura', 'icono' => 'shield', 'orden' => 6],
            ['nombre' => 'Paisajismo y Jardinería', 'icono' => 'leaf', 'orden' => 7],
            ['nombre' => 'Climatización', 'icono' => 'snowflake', 'orden' => 8],
            ['nombre' => 'Techado e Impermeabilización', 'icono' => 'house', 'orden' => 9],
            ['nombre' => 'Demolición y Excavación', 'icono' => 'truck', 'orden' => 10],
            ['nombre' => 'Arquitectura y Diseño', 'icono' => 'compass', 'orden' => 11],
            ['nombre' => 'Administración de Obras', 'icono' => 'clipboard', 'orden' => 12],
        ];

        foreach ($rubros as $rubro) {
            Rubro::create($rubro);
        }
    }
}
