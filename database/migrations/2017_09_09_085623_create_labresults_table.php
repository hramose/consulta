<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabresultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labexams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned()->index();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->dateTime('date');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('labresults', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned()->index();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->dateTime('date');
            $table->string('name');
            $table->string('url');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('appointment_labexam', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('appointment_id')->unsigned()->index();
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->integer('labexam_id')->unsigned()->index();
            $table->foreign('labexam_id')->references('id')->on('labexams')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_labexam');
        Schema::dropIfExists('labresults');
        Schema::dropIfExists('labexams');
        
    }
}
