<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServerIdToEasyPanelVirtualHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('easy_panel_virtual_hosts', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id')->nullable()->index();
            $table->foreign('server_id')->references('id')->on('servers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('easy_panel_virtual_hosts', function (Blueprint $table) {
            $table->dropForeign('easy_panel_virtual_hosts_server_id_foreign');
            $table->dropColumn('server_id');
        });
    }
}
