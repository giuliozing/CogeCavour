<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorsosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corsos', function (Blueprint $table) {
            $table->id();

            $table->string('titolo', 255);
            $table->text('descrizione');
            $table->string('nome_cognome_responsabile', 255);
            $table->string('email_responsabile', 255);
            $table->text('ospiti')->nullable();

            $table->string('giorno', 255);
            $table->string('ora', 255);

            $table->boolean('materia_sessuale');

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
        Schema::dropIfExists('corsos');
    }
}
