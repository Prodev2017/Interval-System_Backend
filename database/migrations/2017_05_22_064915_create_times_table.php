<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('interval_id');
            $table->bigInteger('interval_projectid');
            $table->bigInteger('interval_moduleid');
            $table->bigInteger('interval_taskid');
            $table->bigInteger('interval_worktypeid');
            $table->bigInteger('interval_personid');
            $table->date('interval_date');
            $table->double('interval_time',6,2);
            $table->text('interval_description');
            $table->boolean('interval_billable');
            $table->text('interval_module');
            $table->text('interval_project');
            $table->string('interval_worktype');
            $table->bigInteger('interval_tasklocalid');
            $table->text('interval_task');
            $table->boolean('interval_active');
            $table->bigInteger('interval_clientid');
            $table->text('interval_client');
            $table->boolean('interval_clientactive');
            $table->integer('interval_statusid');
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
        Schema::dropIfExists('times');
    }
}