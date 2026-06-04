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
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['empresa', 'trabajador', 'oferta']);
            $table->unsignedBigInteger('referencia_id');
            $table->string('motivo', 100);
            $table->text('descripcion')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('estado', ['pendiente', 'revisado', 'resuelto', 'descartado'])->default('pendiente');
            $table->text('accion_tomada')->nullable();
            $table->timestamp('fecha_revision')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
