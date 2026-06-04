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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre_empresa', 200);
            $table->string('razon_social', 100)->nullable();
            $table->text('descripcion')->nullable();
            $table->foreignId('rubro_id')->nullable()->constrained('rubros')->nullOnDelete();
            $table->string('cuit', 20)->nullable()->unique();
            $table->foreignId('provincia_id')->nullable()->constrained('provincias')->nullOnDelete();
            $table->string('telefono', 20)->nullable();
            $table->string('email_contacto', 100)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('domicilio', 100)->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
