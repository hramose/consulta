<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appointment_id')->unsigned()->index();
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('medical_care')->default(2);
            $table->integer('treatment')->default(2);
            $table->integer('satisfaction')->default(2);
            $table->tinyInteger('completed')->unsigned()->default(0);
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
        Schema::dropIfExists('polls');
    }
}
