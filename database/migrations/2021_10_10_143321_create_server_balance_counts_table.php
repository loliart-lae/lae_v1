<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServerBalanceCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_balance_counts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('server_id')->nullable()->index();
            $table->foreign('server_id')->references('id')->on('servers')->cascadeOnDelete();

            $table->unsignedDouble('value')->default(0)->index();

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
        Schema::dropIfExists('server_balance_count');
    }
}
