<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateToLxdContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lxd_containers', function (Blueprint $table) {
            $table->unsignedBigInteger('template_id')->index();
            $table->foreign('template_id')->references('id')->on('lxd_templates');
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
