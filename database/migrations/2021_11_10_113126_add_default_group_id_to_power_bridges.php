<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultGroupIdToGroupBridges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_bridges', function (Blueprint $table) {
            // 集群默认组ID。需要设置默认组才可以启用自动注册。
            $table->unsignedBigInteger('default_group_id')->index()->nullable()->after('allow_auto_register');
            $table->foreign('default_group_id')->references('id')->on('group_bridge_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_bridges', function (Blueprint $table) {
            //
        });
    }
}
