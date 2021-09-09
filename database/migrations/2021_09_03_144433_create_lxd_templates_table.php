<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLxdTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lxd_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->integer('cpu')->index();
            $table->integer('mem')->index();
            $table->integer('disk')->index();

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
        Schema::dropIfExists('lxd_templates');
    }
}
