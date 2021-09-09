<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageIdToLxdContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lxd_containers', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->index();
            $table->foreign('image_id')->references('id')->on('lxd_images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lxd_containers', function (Blueprint $table) {
            //
        });
    }
}
