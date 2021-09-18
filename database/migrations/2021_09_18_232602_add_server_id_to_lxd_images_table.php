<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServerIdToLxdImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lxd_images', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id')->nullable()->index();
            $table->foreign('server_id')->references('id')->on('servers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lxd_images', function (Blueprint $table) {
            $table->dropForeign('server_id');
            $table->dropColumn('server_id');
        });
    }
}
