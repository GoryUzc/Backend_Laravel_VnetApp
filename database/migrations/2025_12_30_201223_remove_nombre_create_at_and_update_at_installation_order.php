<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        Schema::table('installation_order', function (Blueprint $table) {


            $table->dropColumn('installed_at');
            $table->dropColumn('updated_ap');
            // $table->dropForeign(['nombre_campo']);
            // $table->dropColumn('nombre_campo');
            
            // Si tiene índice simple (no primary/foreign):
            // $table->dropIndex(['nombre_campo']);
            // $table->dropColumn('nombre_campo');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::table('nombre_tabla', function (Blueprint $table) {
            // Aquí debes restaurar el campo EXACTAMENTE como estaba
            // Necesitas conocer: tipo, atributos (nullable, default, etc.)
            // Ejemplo:
            $table->string('installed_at')->nullable();
            $table->string('updated_ap')->nullable();
        });
    }
};