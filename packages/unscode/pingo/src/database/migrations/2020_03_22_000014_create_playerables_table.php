<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playerables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('player_id')->unsigned();
            $table->integer('playerable_id')->unsigned();
            $table->string('playerable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playerables');
    }
}
