<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransferBridgeGroupIdToTransferBridgeGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_bridge_guests', function (Blueprint $table) {
            // Transfer Bridge 组权限配置
            $table->unsignedBigInteger('transfer_bridge_group_id')->index()->nullable();
            $table->foreign('transfer_bridge_group_id')->references('id')->on('transfer_bridge_groups')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_bridge_guests', function (Blueprint $table) {
            //
        });
    }
}