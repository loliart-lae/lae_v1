<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePterodactylTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pterodactyl_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->integer('cpu_limit')->default(100)->index();
            $table->integer('memory')->default(1024)->index();
            $table->integer('disk_space')->default(100)->index();
            $table->integer('swap')->default(0)->index();
            $table->integer('io')->default(500)->index();
            $table->integer('databases')->default(1)->index();
            $table->integer('backups')->default(5)->index();

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
        Schema::dropIfExists('pterodactyl_templates');
    }
}
