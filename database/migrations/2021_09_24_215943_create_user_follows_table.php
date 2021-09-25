<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // public function up()
    // {
    //     Schema::create('user_follows', function (Blueprint $table) {
    //         $table->id();

    //         $table->unsignedBigInteger('user_id')->index();
    //         $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

    //         $table->unsignedBigInteger('followed_user_id')->index();
    //         $table->foreign('followed_user_id')->references('id')->on('users')->onDelete('cascade');

    //         $table->timestamps();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('user_follows');
    // }
}
