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
            $table->dropUnique(['ppoe_user']);
            $table->dropUnique(['ppoe_password']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installation_order', function (Blueprint $table) {
            $table->unique('ppoe_user');
            $table->unique('ppoe_password');
        });
    }
};
