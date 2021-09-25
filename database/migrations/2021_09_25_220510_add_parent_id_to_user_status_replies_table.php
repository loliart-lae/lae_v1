<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToUserStatusRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_status_replies', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->index();
            $table->foreign('parent_id')->references('id')->on('user_status_replies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_replies', function (Blueprint $table) {
            //
        });
    }
}
