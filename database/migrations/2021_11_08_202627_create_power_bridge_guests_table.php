<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePowerBridgeGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Power Bridge 客户机
        Schema::create('power_bridge_guests', function (Blueprint $table) {
            $table->id();

            // 显示名称
            $table->string('name')->index();

            // 唯一ID
            $table->string('unique_id')->index()->unique();

            // 是否启用，如果为禁用状态，客户机将被立即下线并且无法登录。
            $table->boolean('enabled')->index()->default(true);

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
        Schema::dropIfExists('power_bridge_guests');
    }
}