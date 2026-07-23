<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Postgres: change column type to varchar and allow nulls
        DB::statement("ALTER TABLE sessions ALTER COLUMN user_id TYPE varchar USING user_id::varchar");
        DB::statement("ALTER TABLE sessions ALTER COLUMN user_id DROP NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Attempt to convert back to bigint where possible; non-numeric values become NULL.
        DB::statement("ALTER TABLE sessions ALTER COLUMN user_id TYPE bigint USING (CASE WHEN user_id ~ '^[0-9]+$' THEN user_id::bigint ELSE NULL END)");
        DB::statement("ALTER TABLE sessions ALTER COLUMN user_id SET NOT NULL");
    }
};
