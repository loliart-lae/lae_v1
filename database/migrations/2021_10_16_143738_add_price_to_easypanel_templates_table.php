<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceToEasyPanelTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('easy_panel_templates', function (Blueprint $table) {
            $table->unsignedInteger('price')->default(0.01)->index()->after('speed_limit');
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
            $table->dropColumn('price');
        });
    }
}
