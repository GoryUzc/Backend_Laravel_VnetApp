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
        Schema::create('installation_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_meeting')->constrained('meetings')->onDelete('cascade');
            $table->foreignId('prospect_aradial_id')->constrained('prospect_aradial')->onDelete('cascade');
            $table->integer('ont_puerto_1');
            $table->integer('conector_sc_pc');
            $table->integer('patch_cord_scpc-scapc');
            $table->integer('roseta');
            $table->integer('adapter_scapc');
            $table->integer('ont_4_puertos');
            $table->integer('conector_sc_upc');
            $table->integer('canaletas');
            $table->integer('ramplug');
            $table->integer('cable_drop');
            $table->integer('hilos');
            $table->string('potencia_recibida_ont');
            $table->string('mac_ont');
            $table->string('serial_ont');
            $table->string('puerto_nap');
            $table->string('ppoe_user')->unique();
            $table->string('ppoe_password')->unique();
            $table->string('ubicacion_onu');
            $table->string('nro_equipos_conectar');
            $table->string('puerto_olt');
            $table->string('etiqueta_cliente');
            $table ->string('router');
            $table->string('detalles_instalacion')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instalallation_order');
    }
};