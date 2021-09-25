<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsLikedToUserStatusLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_status_likes', function (Blueprint $table) {
            $table->boolean('is_liked')->index(); // 用于检测是否重复点赞
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_status_likes', function (Blueprint $table) {
            //
        });
    }
}
