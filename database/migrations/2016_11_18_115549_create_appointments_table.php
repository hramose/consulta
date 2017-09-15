<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned()->index();
            //$table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('created_by')->unsigned()->default(0);
            $table->integer('office_id')->unsigned()->default(0);
            $table->dateTime('date');
            $table->string('start');
            $table->string('end');
            $table->tinyInteger('allDay')->default(0); //1 iniciada
            $table->string('title');
            $table->string('backgroundColor');
            $table->string('borderColor');
            $table->text('medical_instructions')->nullable();
            $table->text('office_info')->nullable();
            $table->tinyInteger('status')->default(0); //1 iniciada //2 no asistio
            $table->tinyInteger('finished')->default(0); //1 finalizada
            $table->tinyInteger('visible_at_calendar')->default(1); //1 finalizada
            
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
        Schema::dropIfExists('appointments');
    }
}
