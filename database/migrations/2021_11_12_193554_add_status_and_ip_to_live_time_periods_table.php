<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndIpToLiveTimePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('live_time_periods', function (Blueprint $table) {
            $table->string('ip')->index()->after('user_id');
            $table->boolean('status')->index()->default(false)->after('ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('live_time_periods', function (Blueprint $table) {
            //
        });
    }
}