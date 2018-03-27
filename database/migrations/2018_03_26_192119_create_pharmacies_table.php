<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePharmaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->string('province');
            $table->string('canton');
            $table->string('district');
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('ide')->nullable();
            $table->string('ide_name')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->tinyInteger('notification')->default(0);
            $table->dateTime('notification_date');
            $table->string('address_map')->nullable();
            $table->tinyInteger('active')->default(0);
        

            $table->timestamps();
        });

         Schema::create('pharmacy_user', function (Blueprint $table) {
    
            $table->integer('pharmacy_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->primary(['pharmacy_id', 'user_id']);

           
        });

          Schema::create('assistants_pharmacies', function (Blueprint $table) {
            $table->integer('assistant_id')->unsigned();
            $table->integer('pharmacy_id')->unsigned();

            $table->foreign('pharmacy_id')->references('id')->on('pharmacies')->onDelete('cascade');
            $table->foreign('assistant_id')->references('id')->on('users')->onDelete('cascade');

            $table->primary(['pharmacy_id', 'assistant_id']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacy_user');
        Schema::dropIfExists('assistants_pharmacies');
        Schema::dropIfExists('pharmacies');
    }
}
