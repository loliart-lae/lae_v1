<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferBridgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 数据桥 集群
        Schema::create('transfer_bridges', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->uuid('uuid')->index()->unique();

            // 集群总开关，关闭后集群将不再收发任何数据
            $table->boolean('enabled')->index()->default(true);

            // 集群自动注册开关。关闭后将不允许通过uuid加入集群。开启后通过UUID加入的集群将自动加入默认组中。如果没有设置默认组，则该选项将被禁用。
            $table->boolean('allow_auto_register')->index()->default(true);

            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects');

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
        Schema::dropIfExists('transfer_bridges');
    }
}