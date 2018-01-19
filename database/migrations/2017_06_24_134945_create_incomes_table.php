<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index(); // medico
            $table->integer('appointment_id')->unsigned()->index(); // consulta
            $table->integer('office_id')->unsigned()->index(); // clinica
            $table->double('amount')->default(0);
            $table->double('pending')->default(0);
            $table->dateTime('date');
            $table->integer('month');
            $table->integer('year');
            $table->string('period_from');
            $table->string('period_to');
            $table->double('subscription_cost')->default(0);
            $table->string('type', 2);
            $table->string('medic_type', 2);
            $table->text('description')->nullable();
            $table->string('purchase_operation_number')->nullable();
            $table->tinyInteger('paid')->default(0); //1 pagada
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
        Schema::dropIfExists('incomes');
    }
}
