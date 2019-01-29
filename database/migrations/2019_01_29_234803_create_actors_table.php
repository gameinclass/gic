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
            $table->increments('id')
                ->comment("Chave primaria para atores | Actor's primary key");
            $table->integer('user_id')
                ->unique()
                ->unsigned()
                ->comment("Chave estrangeira para usuários | User's foreign key");
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // On delete cascade
            $table->boolean('is_administrator')
                ->default(false)
                ->comment("Determina se o ator é administrador  | Determine if the actor is administrator");
            $table->boolean('is_design')
                ->default(false)
                ->comment("Determina se o ator é o design do jogo | Determine if the actor is game design");
            $table->boolean('is_player')
                ->default(true)
                ->comment("Determina se o ator é o jogador  | Determine if the actor is game player");
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
