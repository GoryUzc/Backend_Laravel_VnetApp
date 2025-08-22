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
        Schema::create('franchises', function (Blueprint $table) {
            $table->id(); // id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
            $table->string('branch_office'); // varchar(255)
            $table->unsignedBigInteger('franchise_id')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchises');
    }
};