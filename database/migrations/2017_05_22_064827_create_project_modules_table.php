<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('interval_id');
            $table->bigInteger('interval_projectid');
            $table->bigInteger('interval_moduleid');
            $table->text('interval_description');
            $table->boolean('interval_active');
            $table->text('interval_modulename');
            $table->text('interval_module');
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
        Schema::dropIfExists('project_modules');
    }
}
