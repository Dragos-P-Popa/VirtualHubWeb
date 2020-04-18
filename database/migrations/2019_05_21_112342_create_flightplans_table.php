<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flightplans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bundle');
            $table->integer('departure');
            $table->integer('arrival');
            $table->integer('distance');
            $table->string('waypoints');
            $table->time('duration');
            $table->boolean('public');
            $table->integer('aircraft');
            $table->integer('author');
            $table->string('status');
            $table->string('callsign');
            $table->boolean('realworld');
            $table->integer('airline');
            $table->integer('reported');
            $table->boolean('featured');
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
        Schema::dropIfExists('flightplans');
    }
}
