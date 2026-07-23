<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code');
            $table->string('name');
            $table->string('url')->nullable();;
            $table->string('icon')->nullable();
            $table->ulid('main_menu')->nullable();
            $table->integer('menu_hassub')->nullable();
            $table->integer('sort')->default(0);
            $table->integer('active')->default(1);

            $table->ulid('created_by')->nullable();
            $table->ulid('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};