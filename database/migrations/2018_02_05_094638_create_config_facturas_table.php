<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('nombre');
            $table->string('nombre_comercial');
            $table->char('tipo_identificacion', 2);
            $table->string('identificacion');
            $table->integer('sucursal');
            $table->integer('pos');
            $table->string('codigo_pais_tel')->nullable();
            $table->string('telefono')->nullable();
            $table->string('codigo_pais_fax')->nullable();
            $table->string('fax')->nullable();
            $table->char('provincia', 1);
            $table->char('canton', 2);
            $table->char('distrito', 2);
            $table->char('barrio', 2)->nullable();
            $table->string('otras_senas');
            $table->string('email');
            $table->integer('consecutivo_inicio')->default(1);
            $table->string('atv_user');
            $table->string('atv_password');
            $table->string('pin_certificado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_facturas');
    }
}
