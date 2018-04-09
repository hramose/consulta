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
            $table->string('consecutivo_hacienda')->nullable(); // con hacienda
            $table->integer('user_id')->unsigned()->index(); // medico
            $table->integer('office_id')->unsigned()->index(); // clinica
            $table->integer('obligado_tributario_id')->unsigned()->index(); // medico
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            $table->double('discount')->default(0);
            $table->double('subtotal')->default(0);
            $table->double('total')->default(0);
            $table->double('pay_with')->default(0);
            $table->double('change')->default(0);
            $table->tinyInteger('status')->default(0); //1 facturada
            $table->string('clave_fe')->nullable();
            $table->string('status_fe')->nullable();
            $table->text('resp_hacienda')->nullable();
            $table->char('medio_pago', 2)->default('01');
            $table->char('condicion_venta', 2)->default('01');
            $table->tinyInteger('sent_to_hacienda')->default(0); //1 si ha sido enviado a hacienda para su aprobacio o rechazo
            $table->tinyInteger('created_xml')->default(0);
            $table->char('tipo_documento')->default('01'); // factura
            $table->tinyInteger('fe')->default(0); //utiliza factura electronica
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
