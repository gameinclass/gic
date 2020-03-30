<?php

use Illuminate\Database\Seeder;

class ScoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\Unscode\Pingo\Models\Score::class, 600)->create()->each(function ($score) {
            // Cada ponto criado será atribuido a 15 jogo aleatórios.
            // Atribui o ponto a vários jogos aleatório.
            $game = \Unscode\Pingo\Models\Game::all()->random(15);
            $score->games()->sync($game);
        });
    }
}
