<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupBridgeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_bridge_groups', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index()->unique();

            // 是否启用组，如果为禁用状态，组内客户机将被立即下线并且无法登录。
            $table->boolean('enabled')->index()->default(true);

            $table->unsignedBigInteger('group_bridge_id')->index();
            $table->foreign('group_bridge_id')->references('id')->on('group_bridges');

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
        Schema::dropIfExists('group_bridge_groups');
    }
}
