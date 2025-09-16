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
        schema::create('prospect_aradial', function (Blueprint $table){
            $table->id();
            $table->string('aradial_id');
            $table->string('name');
            $table->string('last_name');
            $table->string('document');
            $table->string('document_type');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('email');
            $table->string('plan');
            $table->string('status_red')->default('inactivo');
            $table->string('otp')->nullable();
            $table->foreignId('franchise_id')->constrained('franchises');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospect_aradial');
    }
};