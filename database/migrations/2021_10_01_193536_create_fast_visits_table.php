<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFastVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fast_visits', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index()->comment('用户自定义的名称');

            $table->string('slug')->index()->comment('随机标签');

            $table->string('uri')->index()->comment('跳转的目标URI');

            $table->boolean('status')->index()->comment('状态，启用，禁用，封禁。');

            $table->string('show_ad')->index()->comment('展示广告');
            $table->string('times')->index()->comment('调用次数');

            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->unsignedBigInteger('domain_id')->index();
            $table->foreign('domain_id')->references('id')->on('fast_visit_domains');

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
        Schema::dropIfExists('fast_visits');
    }
}
