<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyberPanelPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyber_panel_packages', function (Blueprint $table) {
            $table->id();

            $table->string('display_name')->index();
            $table->string('name')->index();

            $table->unsignedInteger('domains')->index()->default(1);
            $table->unsignedInteger('disks')->index()->default(500)->comment('MB');
            $table->unsignedInteger('bandwidth')->index()->default(10240)->comment('MB');
            $table->unsignedInteger('ftp_users')->index()->default(5);
            $table->unsignedInteger('databases')->index()->default(5);
            $table->unsignedInteger('mails')->index()->default(5);

            $table->unsignedBigInteger('server_id')->nullable()->index();
            $table->foreign('server_id')->references('id')->on('servers');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cyber_panel_packages');
    }
}