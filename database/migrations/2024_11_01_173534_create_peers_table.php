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
        Schema::create('peers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->string('public_key')->index();
            $table->string('preshared_key');
            $table->string('allowed_ips')->unique()->index();
            $table->string('persistent_keepalive')->default("25"); 
            $table->dateTime('active')->nullable(); 
            $table->uuid('user_id')->index();
            $table->uuid('wg_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peers');
    }
};
