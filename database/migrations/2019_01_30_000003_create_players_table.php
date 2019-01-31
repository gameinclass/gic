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
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment("Chave estrangeira para usuários | User's foreign key");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // On delete cascade
            $table->integer('game_id')->unsigned()->comment("Chave estrangeira para jogos | Game's foreign key");
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade'); // On delete cascade
            $table->integer('group_id')->unsigned()->nullable()->comment("Chave estrangeira para grupos | Group's foreign key");
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade'); // On delete cascade
            $table->timestamps();
            // Evita que um tenha duplicação no registro de um usuário para o mesmo jogo.
            $table->unique(['user_id', 'game_id']);
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
