<?php

namespace Database\Seeders;

use App\Models\Especialidad;
use Illuminate\Database\Seeder;

class EspecialidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            ['nombre' => 'Albañil', 'descripcion' => 'Construcción y mampostería'],
            ['nombre' => 'Electricista', 'descripcion' => 'Instalaciones eléctricas residenciales y comerciales'],
            ['nombre' => 'Plomero', 'descripcion' => 'Instalaciones de agua y gas'],
            ['nombre' => 'Carpintero', 'descripcion' => 'Trabajos en madera y muebles'],
            ['nombre' => 'Pintor', 'descripcion' => 'Pintura de interiores y exteriores'],
            ['nombre' => 'Soldador', 'descripcion' => 'Soldadura industrial y construcción'],
            ['nombre' => 'Mecánico', 'descripcion' => 'Reparación de vehículos y maquinaria'],
            ['nombre' => 'Jardinero', 'descripcion' => 'Mantenimiento de jardines y áreas verdes'],
            ['nombre' => 'Techista', 'descripcion' => 'Instalación y reparación de techos'],
            ['nombre' => 'Cerrajero', 'descripcion' => 'Instalación y reparación de cerraduras'],
            ['nombre' => 'Herrero', 'descripcion' => 'Trabajos en metal y herrería'],
            ['nombre' => 'Operador de Maquinaria', 'descripcion' => 'Manejo de equipos pesados'],
            ['nombre' => 'Instalador de Drywall', 'descripcion' => 'Instalación de placas de yeso'],
            ['nombre' => 'Vidriero', 'descripcion' => 'Instalación y reparación de vidrios'],
            ['nombre' => 'Ayudante General', 'descripcion' => 'Apoyo en diversas tareas de construcción'],
            ['nombre' => 'Otro', 'descripcion' => null],
        ];

        foreach ($especialidades as $esp) {
            Especialidad::create($esp);
        }
    }
}
