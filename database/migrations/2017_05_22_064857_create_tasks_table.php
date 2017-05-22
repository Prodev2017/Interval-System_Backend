<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('interval_id');
            $table->bigInteger('interval_localid');
            $table->bigInteger('interval_assigneeid');
            $table->bigInteger('interval_statusid');
            $table->bigInteger('interval_projectid');
            $table->bigInteger('interval_moduleid');
            $table->string('interval_title');
            $table->bigInteger('interval_ownerid');
            $table->string('interval_status');
            $table->bigInteger('interval_status_order');
            $table->string('interval_project');
            $table->bigInteger('interval_clientid');
            $table->bigInteger('interval_clientlocalid');
            $table->text('interval_client');
            $table->increments('interval_module');
            $table->bigInteger('interval_projectlocalid');
            $table->text('interval_assignees');
            $table->text('interval_owners');
            $table->double('interval_billable',10,3);
            $table->double('interval_unbillable',10,3);
            $table->double('interval_actual',10,3);
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
        Schema::dropIfExists('tasks');
    }
}