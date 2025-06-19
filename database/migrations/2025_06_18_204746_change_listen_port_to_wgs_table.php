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
        Schema::table('wgs', function (Blueprint $table) {
            $table->dropUnique('wgs_listen_port_unique');

            $table->unique(['listen_port', 'server_id'], 'wgs_listen_port_server_id_unique');

            $table->index('listen_port', 'wgs_listen_port_index');
            $table->index('server_id', 'wgs_server_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wgs', function (Blueprint $table) {
           
            $table->dropIndex('wgs_listen_port_index');
            $table->dropIndex('wgs_server_id_index');
            
            $table->dropUnique('wgs_listen_port_server_id_unique');

            $table->unique('listen_port', 'wgs_listen_port_unique');
        });
    }
};
