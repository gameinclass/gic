<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment("Chave estrangeira para usuário");
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('game_id')->unsigned()->nullable()->comment("Chave estrangeira para jogos");
            $table->foreign('game_id')->references('id')->on('games');
            $table->integer('phase_id')->unsigned()->comment("Chave estrangeira para fases");
            $table->foreign('phase_id')->references('id')->on('phases');
            $table->integer('group_id')->unsigned()->nullable()->comment("Chave estrangeira para grupos");
            $table->foreign('group_id')->references('id')->on('groups');
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
        Schema::dropIfExists('players');
    }
}
