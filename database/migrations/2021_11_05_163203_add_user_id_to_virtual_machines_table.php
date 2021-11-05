<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToVirtualMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtual_machines', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->index()->nullable()->after('project_id');
            $table->foreign('user_id')->references('id')->on('virtual_machine_users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('virtual_machines', function (Blueprint $table) {
            //
        });
    }
}
