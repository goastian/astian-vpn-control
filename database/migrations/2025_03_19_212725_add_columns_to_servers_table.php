<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string("ss_port", 10)->nullable();
            $table->string("ss_password", 190)->nullable();
            $table->string("ss_method", 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('server', function (Blueprint $table) {
            $table->dropColumn("ss_port", 10);
            $table->dropColumn("ss_password", 190);
            $table->dropColumn("ss_method", 150);
        });
    }
};
