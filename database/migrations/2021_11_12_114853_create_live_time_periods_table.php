<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveTimePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_time_periods', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index(); // 场景名称

            $table->string('token')->index(); // 推流密钥

            $table->time('start_at')->index(); // 从何时开始
            $table->time('end_at')->index(); // 从何时结束

            // 谁在直播
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('live_time_periods');
    }
}