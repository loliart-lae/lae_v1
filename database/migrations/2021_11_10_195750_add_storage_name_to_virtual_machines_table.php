<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStorageNameToVirtualMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtual_machines', function (Blueprint $table) {
            $table->dropColumn('ip_address');
            $table->string('storage_name')->index()->nullable()->after('bios');
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