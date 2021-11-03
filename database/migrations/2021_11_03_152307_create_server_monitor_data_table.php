<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServerMonitorDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_monitor_data', function (Blueprint $table) {
            $table->id();

            $table->string('hostname')->index();

            $table->unsignedFloat('cpu_usage')->index()->default(0);
            $table->unsignedFloat('mem_usage')->index()->default(0);

            $table->unsignedFloat('disk_usage')->index()->default(0);

            $table->unsignedFloat('upload_speed')->index()->default(0);
            $table->unsignedFloat('download_speed')->index()->default(0);


            $table->unsignedBigInteger('monitor_id')->index();
            $table->foreign('monitor_id')->references('id')->on('server_monitors')->cascadeOnDelete();

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
        Schema::dropIfExists('server_monitor_data');
    }
}
