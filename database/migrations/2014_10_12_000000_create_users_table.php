<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60)->nullable();
            $table->string('api_token', 90)->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->string('provider')->default('email');
            $table->string('provider_id')->unique();
            $table->float('rating_service_cache',2,1)->unsigned()->default(3.0);
            $table->float('rating_medic_cache',2,1)->unsigned()->default(3.0);
            $table->integer('rating_service_count')->unsigned()->default(0);
            $table->integer('rating_medic_count')->unsigned()->default(0);


            $table->rememberToken();
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
        Schema::drop('users');
    }
}
