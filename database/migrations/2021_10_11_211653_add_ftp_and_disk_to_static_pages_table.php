<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFtpAndDiskToStaticPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('static_pages', function (Blueprint $table) {
            $table->string('ftp_username')->nullable()->index();
            $table->string('ftp_password')->nullable()->index();
            $table->unsignedDouble('used_disk')->index()->nullable(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('static_pages', function (Blueprint $table) {
            //
        });
    }
}
