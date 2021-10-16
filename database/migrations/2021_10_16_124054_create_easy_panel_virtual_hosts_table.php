<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEasyPanelVirtualHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('easy_panel_virtual_hosts', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->string('status')->default('pending')->index();

            $table->string('username')->index();
            $table->string('password')->index();


            $table->unsignedBigInteger('template_id')->index();
            $table->foreign('template_id')->references('id')->on('easy_panel_templates');

            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('easy_panel_virtual_hosts');
    }
}
