<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferBridgePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_bridge_permissions', function (Blueprint $table) {
            $table->id();

            $table->json('config')->nullable();

            // Transfer Bridge 集群权限
            $table->unsignedBigInteger('transfer_bridge_id')->index()->nullable();
            $table->foreign('transfer_bridge_id')->references('id')->on('transfer_bridges')->cascadeOnDelete();

            // Transfer Bridge 组权限配置
            $table->unsignedBigInteger('transfer_bridge_group_id')->index()->nullable();
            $table->foreign('transfer_bridge_group_id')->references('id')->on('transfer_bridge_groups')->cascadeOnDelete();

            // Transfer Bridge 客户机权限配置
            $table->unsignedBigInteger('transfer_bridge_guest_id')->index()->nullable();
            $table->foreign('transfer_bridge_guest_id')->references('id')->on('transfer_bridge_guests')->cascadeOnDelete();

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
        Schema::dropIfExists('transfer_bridge_permissions');
    }
}