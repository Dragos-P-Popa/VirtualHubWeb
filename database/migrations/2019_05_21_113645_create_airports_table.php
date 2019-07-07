<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('iata');
            $table->string('icao');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('timezone');
            $table->string('search');
            $table->float('longitude');
            $table->float('latitude');
            $table->integer('altitude');
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
        Schema::dropIfExists('airports');
    }
}
