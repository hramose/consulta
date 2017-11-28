<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('consecutivo')->unsigned(); // medico
            $table->integer('user_id')->unsigned()->index(); // medico
            $table->integer('appointment_id')->unsigned()->index(); // consulta
            $table->integer('office_id')->unsigned()->index(); // clinica
            $table->integer('patient_id')->unsigned()->index(); // paciente
            $table->string('office_type'); // office type
            $table->string('client_name')->nullable();
            $table->double('discount')->default(0);
            $table->double('subtotal')->default(0);
            $table->double('total')->default(0);
            $table->double('pay_with')->default(0);
            $table->double('change')->default(0);
            $table->tinyInteger('status')->default(0); //1 facturada
            $table->char('bill_to', 2);
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
        Schema::dropIfExists('invoices');
    }
}
