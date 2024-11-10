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
        Schema::create('wgs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('private_key');
            $table->string('listen_port', 10);
            $table->string('dns_1', 150)->nullable();
            $table->string('dns_2', 150)->nullable();
            $table->dateTime('active')->nullable();
            $table->uuid('server_id');
            $table->uuid('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wgs');
    }
};
