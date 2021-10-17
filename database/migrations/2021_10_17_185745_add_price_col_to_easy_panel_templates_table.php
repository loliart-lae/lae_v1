<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceColToEasyPanelTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('easy_panel_templates', function (Blueprint $table) {
            $table->double('price', 3)->index()->default(0.001)->after('speed_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('easy_panel_templates', function (Blueprint $table) {
            //
        });
    }
}
