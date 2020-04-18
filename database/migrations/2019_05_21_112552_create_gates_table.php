<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('aircrafts');
            $table->string('airport');
            $table->string('size');
            $table->string('type');
            $table->float('heading');
            $table->float('longitude');
            $table->float('latitude');
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
        Schema::dropIfExists('gates');
    }
}
