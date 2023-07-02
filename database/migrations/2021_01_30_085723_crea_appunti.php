<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaAppunti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('appunti', function (Blueprint $table) {
            $table->id();

            $table->string('titolo');
            $table->string('autore');
            $table->integer('visualizzazioni')->default(0);
            $table->string('insegnante')->nullable();
            $table->string('materia');
            $table->string('testo');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
