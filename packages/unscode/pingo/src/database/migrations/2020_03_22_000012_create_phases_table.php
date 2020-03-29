<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id')
                ->unsigned()
                ->comment("Chave estrangeira para jogos");
            $table->foreign('game_id')
                ->references('id')
                ->on('games');
            $table->string('title')
                ->comment('Título da fase do jogo');
            $table->timestamp('start')
                ->useCurrent()
                ->comment('Data de início para a fase do jogo');
            $table->timestamp('finish')
                ->useCurrent()
                ->comment('Data final para a fase do jogo');
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
        Schema::dropIfExists('phases');
    }
}
