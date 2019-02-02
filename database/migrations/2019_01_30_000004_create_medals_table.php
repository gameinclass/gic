<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment("Chave estrangeira para usuário");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // On delete cascade
            $table->string('title')->comment('Título da medalha');
            $table->text('description')->comment('Descrição detalhada da medalha');
            $table->string('path')->comment('Caminho do arquivo de imagem no disco');
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
        Schema::dropIfExists('medals');
    }
}
