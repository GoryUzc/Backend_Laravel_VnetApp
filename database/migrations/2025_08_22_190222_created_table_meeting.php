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
        Schema::create('meeting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_aradial_id')->constrained('prospect_aradial')->onDelete('cascade'); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->dateTime('date_time1')->nullable();
            $table->string('status')->default('pending'); 
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
        }); 

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting');
    }
};