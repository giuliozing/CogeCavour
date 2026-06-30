<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Creapetizioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petizionis', function (Blueprint $table) {
            $table->id();

            $table->string('titolo');
            $table->string('autore');
            $table->integer('visualizzazioni')->default(0);
            $table->string('testo');
            $table->integer('idautore');
            $table->integer('pro')->default(0);
            $table->integer('contro')->default(0);

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
