<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysicalExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_exams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appointment_id')->unsigned()->index();
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->text('cardio')->nullable();
            $table->text('digestivo')->nullable();
            $table->text('urinario')->nullable();
            $table->text('linfatico')->nullable();
            $table->text('dermatologico')->nullable();
            $table->text('neurologico')->nullable();
            $table->text('osteoarticular')->nullable();
            $table->text('otorrinolaringologico')->nullable();
            $table->text('pulmonar')->nullable();
            $table->text('psiquiatrico')->nullable();
            $table->text('reproductor')->nullable();
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
        Schema::dropIfExists('physical_exams');
    }
}
