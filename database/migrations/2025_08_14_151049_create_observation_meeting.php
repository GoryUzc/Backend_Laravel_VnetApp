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
        Schema::create('observation_dates', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();
            $table->string('prospect_aradial_id')->unique();
            $table->string('meeting_id');
            $table->string('status_meeting'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_dates');}
};
