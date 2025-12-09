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
            $table->timestamp('update_ap')->nullable();
            $table->renameColumn('update_ap', 'updated_ap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installation_order', function (Blueprint $table) {
            $table->renameColumn('updated_ap', 'update_ap');
        });
    }
};
