<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectIdAndVisibilityToLxdImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lxd_images', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->index()->after('image');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->boolean('visibility')->default(0)->comment('可见性')->after('project_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lxd_images', function (Blueprint $table) {
            $table->dropForeign('project_id');
            $table->dropColumn('project_id');
            $table->dropColumn('visibility');
        });
    }
}
