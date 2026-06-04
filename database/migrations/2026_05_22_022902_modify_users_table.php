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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipo', ['admin', 'trabajador', 'empresa', 'reclutador'])->default('trabajador')->after('email');
            $table->boolean('estado')->default(true)->after('tipo');
            $table->boolean('visible_busqueda')->default(true)->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
