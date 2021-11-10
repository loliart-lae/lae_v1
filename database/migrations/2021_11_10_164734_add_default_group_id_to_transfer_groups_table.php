<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultGroupIdToTransferGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_bridges', function (Blueprint $table) {
            // 集群默认组ID。需要设置默认组才可以启用自动注册。
            $table->unsignedBigInteger('default_group_id')->index()->nullable()->after('allow_auto_register');
            $table->foreign('default_group_id')->references('id')->on('transfer_bridge_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_groups', function (Blueprint $table) {
            //
        });
    }
}