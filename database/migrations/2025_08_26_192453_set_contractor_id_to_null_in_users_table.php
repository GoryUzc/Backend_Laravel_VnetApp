<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Cambiar el tipo de contractor_id de string a integer
            $table->unsignedBigInteger('contractor_id')->nullable()->change();
            
            // Ejemplos de otros cambios comunes:
            // $table->string('email', 100)->change(); // Cambiar longitud
            // $table->text('description')->change(); // Cambiar de string a text
            // $table->boolean('is_active')->default(true)->change(); // Cambiar tipo y agregar default
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir el cambio
            $table->string('contractor_id')->nullable()->change();
        });
    }
};
