<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriveFileCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drive_file_cache', function (Blueprint $table) {
            $table->id();

            $table->string('path', 4000);
            $table->string('path_hash')->index();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('drive_file_cache');

            $table->string('name')->index();
            $table->string('fileName')->index()->nullable();
            $table->string('mimetype')->nullable()->index();
            $table->unsignedBigInteger('size')->default(0)->index();

            $table->string('cost_method')->index();

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
        Schema::dropIfExists('drive_file_cache');
    }
}
