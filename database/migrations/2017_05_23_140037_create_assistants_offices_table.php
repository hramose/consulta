<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssistantsOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('assistants_offices', function(Blueprint $table)
        {
            $table->integer('assistant_id')->unsigned();
            $table->integer('office_id')->unsigned();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->foreign('assistant_id')->references('id')->on('users')->onDelete('cascade');

            $table->primary(array('office_id', 'assistant_id'));
           
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('assistants_offices');
    }
}
