<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetCascadeToFastVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fast_visits', function (Blueprint $table) {
            $table->dropForeign('fast_visits_domain_id_foreign');
            $table->dropForeign('fast_visits_project_id_foreign');

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->change();
            $table->foreign('domain_id')->references('id')->on('fast_visit_domains')->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fast_visits', function (Blueprint $table) {
            //
        });
    }
}
