<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColToForwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forwards', function (Blueprint $table) {
            //
            $table->unsignedSmallInteger('from')->update();
            $table->unsignedSmallInteger('to')->update();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forwards', function (Blueprint $table) {
            //
        });
    }
}
