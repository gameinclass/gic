<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')
                ->comment("Chave primária para grupos");
            $table->integer('game_id')
                ->unsigned()
                ->nullable()
                ->comment("Chave estrangeira para jogos");
            $table->foreign('game_id')
                ->references('id')
                ->on('games');
            $table->string('name')
                ->comment('Nome do grupo');
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
        Schema::dropIfExists('groups');
    }
}
