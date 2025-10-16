<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('installation_order', function (Blueprint $table) {
        $table->string('signature_path')->nullable(); // Ruta del archivo en storage
    });
}

public function down()
{
    Schema::table('installation_order', function (Blueprint $table) {
        $table->dropColumn('signature_path');
    });
}
};
