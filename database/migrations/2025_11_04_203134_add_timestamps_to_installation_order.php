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
        Schema::table('installation_order', function (Blueprint $table) {
            $table->timestamp('create_at');
            $table->timestamp('update_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installation_order', function (Blueprint $table) {
            $table->dropColumn(['create_at ' , 'update_ap']);
        });
    }
};
