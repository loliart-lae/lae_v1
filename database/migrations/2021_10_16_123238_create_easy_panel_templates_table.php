<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEasyPanelTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('easy_panel_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->unsignedBigInteger('web_quota')->default(1024);
            $table->unsignedBigInteger('db_quota')->default(1024);
            $table->boolean('subDir_flag')->default(1);
            $table->string('subDir')->default('wwwroot');
            $table->boolean('ftp')->default(1);
            $table->unsignedInteger('ftp_usl')->default(1024);
            $table->unsignedInteger('ftp_dsl')->default(1024);
            $table->unsignedInteger('speed_limit')->default(1024);

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
        Schema::dropIfExists('easy_panel_templates');
    }
}
