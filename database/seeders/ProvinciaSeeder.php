<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provincias = [
            ['nombre' => 'Buenos Aires', 'codigo' => 'BA', 'region' => 'Centro'],
            ['nombre' => 'Ciudad Autónoma de Buenos Aires', 'codigo' => 'CABA', 'region' => 'Centro'],
            ['nombre' => 'Catamarca', 'codigo' => 'CT', 'region' => 'NOA'],
            ['nombre' => 'Chaco', 'codigo' => 'CC', 'region' => 'NEA'],
            ['nombre' => 'Chubut', 'codigo' => 'CH', 'region' => 'Patagonia'],
            ['nombre' => 'Córdoba', 'codigo' => 'CB', 'region' => 'Centro'],
            ['nombre' => 'Corrientes', 'codigo' => 'CR', 'region' => 'NEA'],
            ['nombre' => 'Entre Ríos', 'codigo' => 'ER', 'region' => 'Centro'],
            ['nombre' => 'Formosa', 'codigo' => 'FO', 'region' => 'NEA'],
            ['nombre' => 'Jujuy', 'codigo' => 'JY', 'region' => 'NOA'],
            ['nombre' => 'La Pampa', 'codigo' => 'LP', 'region' => 'Centro'],
            ['nombre' => 'La Rioja', 'codigo' => 'LR', 'region' => 'NOA'],
            ['nombre' => 'Mendoza', 'codigo' => 'MZ', 'region' => 'Cuyo'],
            ['nombre' => 'Misiones', 'codigo' => 'MI', 'region' => 'NEA'],
            ['nombre' => 'Neuquén', 'codigo' => 'NQ', 'region' => 'Patagonia'],
            ['nombre' => 'Río Negro', 'codigo' => 'RN', 'region' => 'Patagonia'],
            ['nombre' => 'Salta', 'codigo' => 'SA', 'region' => 'NOA'],
            ['nombre' => 'San Juan', 'codigo' => 'SJ', 'region' => 'Cuyo'],
            ['nombre' => 'San Luis', 'codigo' => 'SL', 'region' => 'Cuyo'],
            ['nombre' => 'Santa Cruz', 'codigo' => 'SC', 'region' => 'Patagonia'],
            ['nombre' => 'Santa Fe', 'codigo' => 'SF', 'region' => 'Centro'],
            ['nombre' => 'Santiago del Estero', 'codigo' => 'SE', 'region' => 'NOA'],
            ['nombre' => 'Tierra del Fuego', 'codigo' => 'TF', 'region' => 'Patagonia'],
            ['nombre' => 'Tucumán', 'codigo' => 'TM', 'region' => 'NOA'],
        ];

        foreach ($provincias as $provincia) {
            \App\Models\Provincia::create($provincia);
        }
    }
}
