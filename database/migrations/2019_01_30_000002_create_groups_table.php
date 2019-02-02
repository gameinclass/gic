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
            $table->increments('id')->comment("Chave primaria para grupos");
            $table->integer('game_id')->unsigned()->comment("Chave estrangeira para jogos");
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade'); // On delete cascade
            $table->string('name')->comment('Nome do grupo');
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
