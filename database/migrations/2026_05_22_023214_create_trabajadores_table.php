<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trabajadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('dni', 20)->nullable()->unique();
            $table->string('apellido', 50)->nullable();
            $table->string('nombre', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('anios_experiencia')->default(0);
            $table->string('curriculum_pdf', 255)->nullable();
            $table->string('domicilio', 100)->nullable();
            $table->foreignId('provincia_preferencia_id')->nullable()->constrained('provincias')->nullOnDelete();
            $table->foreignId('localidad_preferencia_id')->nullable()->constrained('localidades')->nullOnDelete();
            $table->string('telefono', 20)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('nombre_titulo', 50)->nullable();
            $table->string('imagen_perfil', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajadores');
    }
};
