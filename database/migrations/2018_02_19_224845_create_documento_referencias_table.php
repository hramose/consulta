<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentoReferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_referencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factura_id')->unsigned()->index();
            $table->foreign('factura_id')->references('id')->on('facturas')->onDelete('cascade');
            $table->char('tipo_documento', 2);
            $table->string('numero_documento');
            $table->dateTime('fecha_emision');
            $table->char('codigo_referencia', 2);
            $table->text('razon');

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
        Schema::dropIfExists('documento_referencias');
    }
}
