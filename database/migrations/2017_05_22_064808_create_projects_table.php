<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('interval_id');
            $table->text('interval_name');
            $table->string('interval_alert_percent');
            $table->boolean('interval_active');
            $table->text('interval_client');
            $table->bigInteger('interval_clientid');
            $table->bigInteger('interval_localid');
            $table->text('interval_manager');
            $table->bigInteger('interval_managerid');
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
        Schema::dropIfExists('projects');
    }
}
