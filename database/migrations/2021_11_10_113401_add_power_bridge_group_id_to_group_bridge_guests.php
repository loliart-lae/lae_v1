<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupBridgeGroupIdToGroupBridgeGuests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_bridge_guests', function (Blueprint $table) {
            // Power Bridge 组权限配置
            $table->unsignedBigInteger('group_bridge_group_id')->index()->nullable();
            $table->foreign('group_bridge_group_id')->references('id')->on('group_bridge_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_bridge_guests', function (Blueprint $table) {
            //
        });
    }
}
