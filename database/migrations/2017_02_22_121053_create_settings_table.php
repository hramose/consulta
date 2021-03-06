<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('settings', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('slotDuration')->default('00:30:00')->nullable();
            $table->string('minTime')->default('06:00:00')->nullable();
            $table->string('maxTime')->default('18:00:00')->nullable();
            $table->string('freeDays')->default('["0"]')->nullable();
            $table->tinyInteger('trial')->default(0);
            $table->integer('trial_days')->default(30);
            
        });
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
