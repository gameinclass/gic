<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment("Chave primária para atores");
            $table->integer('user_id')->unique()->unsigned()->comment("Chave estrangeira para usuário");
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('is_administrator')->default(false)->comment("Determina se o ator é administrador");
            $table->boolean('is_design')->default(false)->comment("Determina se o ator é o design do jogo");
            $table->boolean('is_player')->default(true)->comment("Determina se o ator é o jogador");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actors');
    }
}
