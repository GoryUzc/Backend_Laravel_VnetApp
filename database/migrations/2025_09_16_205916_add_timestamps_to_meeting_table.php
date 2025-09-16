<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('meeting', function (Blueprint $table) {
            $table->timestamps(); // ← Esto agrega created_at y updated_at automáticamente
        });
    }

    public function down()
    {
        Schema::table('meeting', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};