<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToDocumentLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_likes', function (Blueprint $table) {
            $table->string('type')->index();
            $table->boolean('is_liked')->index(); // 用于检测是否重复点赞
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents_likes', function (Blueprint $table) {
            //
        });
    }
}
