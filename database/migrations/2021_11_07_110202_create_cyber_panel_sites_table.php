<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyberPanelSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyber_panel_sites', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->string('domain')->index();
            $table->string('owner')->index();
            $table->string('password')->index();

            $table->unsignedBigInteger('package_id')->index();
            $table->foreign('package_id')->references('id')->on('cyber_panel_packages');

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
        Schema::dropIfExists('cyber_panel_sites');
    }
}