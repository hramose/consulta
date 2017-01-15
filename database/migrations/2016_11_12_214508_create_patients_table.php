<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('birth_date');
            $table->string('gender', 2);
            $table->string('phone');
            $table->string('phone2');
            $table->string('email');
            $table->string('address');
            $table->string('province');
            $table->string('city');
            $table->string('photo')->nullable();
            $table->integer('created_by')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('patient_user', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('patient_id')->unsigned()->index();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_user');
        Schema::dropIfExists('patients');
    }
}
