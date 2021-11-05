<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_machines', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->tinyInteger('status')->index()->default(0);

            $table->unsignedBigInteger('template_id')->index();
            $table->foreign('template_id')->references('id')->on('virtual_machine_templates');
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
        Schema::dropIfExists('virtual_machines');
    }
}
