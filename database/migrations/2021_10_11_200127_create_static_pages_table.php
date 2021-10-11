<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaticPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_pages', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->string('description')->index();

            $table->string('domain')->index();

            $table->string('ftp_username')->nullable()->index();
            $table->string('ftp_password')->nullable()->index();

            $table->unsignedDouble('used_disk')->index()->nullable(0);

            $table->unsignedBigInteger('server_id')->index();
            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

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
        Schema::dropIfExists('static_pages');
    }
}
