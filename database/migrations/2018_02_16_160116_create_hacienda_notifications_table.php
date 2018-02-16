<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHaciendaNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hacienda_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('office_id')->unsigned()->index();
            $table->string('title');
            $table->text('body');
            $table->tinyInteger('viewed')->default(0); //0 no vista desde el panel de notificaciones
            $table->tinyInteger('viewed_assistant')->default(0); //0 no vista desde el panel de notificaciones del asistente
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
        Schema::dropIfExists('hacienda_notifications');
    }
}
