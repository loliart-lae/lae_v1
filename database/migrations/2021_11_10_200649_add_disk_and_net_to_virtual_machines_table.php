<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiskAndNetToVirtualMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtual_machines', function (Blueprint $table) {
            $table->string('disk')->index()->nullable()->after('storage_name');
            $table->string('net')->index()->nullable()->after('disk');
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