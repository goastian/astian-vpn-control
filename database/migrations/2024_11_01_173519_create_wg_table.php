<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wgs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug');
            $table->string('subnet', 150)->index();
            $table->string('gateway', 150);
            $table->string('private_key', 150);
            $table->string('listen_port', 10)->unique(); 
            $table->string('dns', 150)->nullable();
            $table->boolean('active')->default(false)->index();
            $table->boolean('mounted')->default(false)->index();
            $table->string('interface', 100);
            $table->uuid('server_id');
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
