<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllocationIdToPterodactylServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pterodactyl_servers', function (Blueprint $table) {
            $table->unsignedBigInteger('allocation_id')->index()->after('user_id');
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
