<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLimitsToVirtualMachineTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtual_machine_templates', function (Blueprint $table) {
            $table->unsignedInteger('disk_read')->default(100)->index()->comment('MB/s')->after('disk');
            $table->unsignedInteger('disk_write')->default(100)->index()->comment('MB/s')->after('disk_read');
            $table->unsignedInteger('network_limit')->default(3)->index()->comment('MB/s')->after('disk_write');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('virtual_machine_templates', function (Blueprint $table) {
            //
        });
    }
}