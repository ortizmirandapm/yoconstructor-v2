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
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('rubro_id')->nullable()->constrained('rubros')->nullOnDelete();
            $table->string('titulo', 200);
            $table->text('descripcion');
            $table->text('requisitos')->nullable();
            $table->decimal('salario_min', 10, 2)->nullable();
            $table->decimal('salario_max', 10, 2)->nullable();
            $table->enum('tipo_contrato', ['Tiempo completo', 'Medio tiempo', 'Por proyecto', 'Pasantía'])->default('Tiempo completo');
            $table->enum('modalidad', ['Presencial', 'Remoto', 'Híbrido'])->default('Presencial');
            $table->foreignId('provincia_id')->nullable()->constrained('provincias')->nullOnDelete();
            $table->foreignId('localidad_id')->nullable()->constrained('localidades')->nullOnDelete();
            $table->integer('experiencia_requerida')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->enum('estado', ['Activa', 'Pausada', 'Cerrada', 'Borrador'])->default('Activa');
            $table->unsignedInteger('visitas')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};
