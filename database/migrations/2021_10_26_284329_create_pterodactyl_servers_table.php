<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePterodactylServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pterodactyl_servers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('template_id')->nullable()->index();
            $table->foreign('template_id')->references('id')->on('pterodactyl_templates');

            $table->unsignedBigInteger('image_id')->nullable()->index();
            $table->foreign('image_id')->references('id')->on('pterodactyl_images');

            $table->unsignedBigInteger('server_id')->nullable()->index();
            $table->foreign('server_id')->references('id')->on('servers');

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
        Schema::dropIfExists('pterodactyl_servers');
    }
}
