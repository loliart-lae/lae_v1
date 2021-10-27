<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePterodactylImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pterodactyl_images', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->integer('egg')->index();
            $table->string('docker_image')->index();
            $table->string('startup')->index();
            $table->string('environment')->index();


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
        Schema::dropIfExists('pterodactyl_images');
    }
}
