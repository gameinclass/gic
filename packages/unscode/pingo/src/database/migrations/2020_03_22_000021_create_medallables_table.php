<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedallablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medallables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('medal_id')->unsigned();
            $table->integer('medallable_id')->unsigned();
            $table->string('medallable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medallables');
    }
}
