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
            $table->integer('office_id')->unsigned()->index(); // clinica
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            $table->double('discount')->default(0);
            $table->double('subtotal')->default(0);
            $table->double('total')->default(0);
            $table->double('pay_with')->default(0);
            $table->double('change')->default(0);
            $table->tinyInteger('status')->default(0); //1 facturada
            $table->char('medio_pago', 2)->default('01');
            $table->char('condicion_venta', 2)->default('01');
            $table->char('tipo_documento')->default('01'); // factura

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
