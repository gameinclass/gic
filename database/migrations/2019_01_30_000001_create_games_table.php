<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment("Chave estrangeira para usuários | User's foreign key");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // On delete cascade
            $table->string('title')->comment('Título do jogo | Game title');
            $table->text('description')->comment('Descrição do jogo | Game description');
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
        Schema::dropIfExists('games');
    }
}
