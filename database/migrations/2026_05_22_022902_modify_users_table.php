<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Columns already added in create_users_table migration.
        // Kept as a no-op to avoid breaking existing migration chains.
    }

    public function down(): void
    {
        //
    }
};
