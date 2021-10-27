<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropServerIdForeignToPterodactylServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pterodactyl_servers', function (Blueprint $table) {
            $table->dropForeign('pterodactyl_servers_server_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pterodactyl_servers', function (Blueprint $table) {
            //
        });
    }
}
