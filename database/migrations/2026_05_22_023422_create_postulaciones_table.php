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
        Schema::create('postulaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oferta_id')->constrained('ofertas')->cascadeOnDelete();
            $table->foreignId('trabajador_id')->constrained('trabajadores')->cascadeOnDelete();
            $table->text('mensaje')->nullable();
            $table->string('cv_adjunto', 255)->nullable();
            $table->text('notas_empresa')->nullable();
            $table->enum('estado', ['Pendiente', 'Revisada', 'Entrevista', 'Aceptada', 'Rechazada'])->default('Pendiente');
            $table->timestamps();

            $table->unique(['oferta_id', 'trabajador_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulaciones');
    }
};
