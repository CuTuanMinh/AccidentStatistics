<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accidents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('timeHappen')->nullable();
            $table->string('dayHappen')->nullable();
            $table->integer('monthHappen')->nullable();
            $table->string('vehicle')->nullable();
            $table->integer('died')->nullable();
            $table->integer('hurt')->nullable();
            $table->string('location')->nullable();
            $table->string('listEntity')->nullable();
            $table->string('url')->nullable();
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
        Schema::dropIfExists('accidents');
    }
}
