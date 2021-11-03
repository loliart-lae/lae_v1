<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServerMonitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_monitors', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();

            $table->boolean('public')->index()->default(false);

            $table->string('token')->index();

            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();

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
        Schema::dropIfExists('server_monitors');
    }
}
