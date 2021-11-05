<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualMachineTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_machine_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable()->index();
            $table->integer('cpu')->default(1)->index();
            $table->integer('memory')->default(1)->index();
            $table->integer('disk')->default(1)->index();
            $table->float('price')->default(0.01)->index();

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
        Schema::dropIfExists('virtual_machine_templates');
    }
}
