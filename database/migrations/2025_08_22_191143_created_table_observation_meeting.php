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
        Schema::create('observation_meeting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->foreignId('prospect_aradial_id')->constrained('prospect_aradial')->onDelete('cascade')->nullable();
            $table->foreignId('meeting_id')->constrained('meetings')->onDelete('cascade')->nullable();
            $table->string('status_meeting'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_meeting');}
};