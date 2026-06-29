<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE ofertas MODIFY COLUMN estado ENUM('Activa', 'Pausada', 'Cerrada', 'Borrador', 'Inactiva') DEFAULT 'Activa'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE ofertas MODIFY COLUMN estado ENUM('Activa', 'Pausada', 'Cerrada', 'Borrador') DEFAULT 'Activa'");
        }
    }
};
