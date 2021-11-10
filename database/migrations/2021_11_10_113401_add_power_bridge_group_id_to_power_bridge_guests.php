<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPowerBridgeGroupIdToPowerBridgeGuests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('power_bridge_guests', function (Blueprint $table) {
            // Power Bridge 组权限配置
            $table->unsignedBigInteger('power_bridge_group_id')->index()->nullable();
            $table->foreign('power_bridge_group_id')->references('id')->on('power_bridge_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('power_bridge_guests', function (Blueprint $table) {
            //
        });
    }
}