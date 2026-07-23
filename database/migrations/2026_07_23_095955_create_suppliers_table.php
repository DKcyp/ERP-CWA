<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('supplier_code', 50)->unique();
            $table->string('name', 150);
            $table->char('supplier_group_id', 26)->nullable();
            $table->char('supplier_center_id', 26)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->integer('term_of_payment')->default(0)->comment('TOP in days');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_group_id')->references('id')->on('supplier_groups')->nullOnDelete();
            $table->foreign('supplier_center_id')->references('id')->on('supplier_centers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
