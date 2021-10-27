<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPterodactylServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pterodactyl_servers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index()->after('project_id');
            $table->foreign('user_id')->references('id')->on('pterodactyl_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pterodactyl_servers', function (Blueprint $table) {
            //
        });
    }
}
