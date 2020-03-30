<?php

use Illuminate\Database\Seeder;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\Unscode\Pingo\Models\Player::class, 1500)->create()->each(function ($player) {
            // Cada jogador criado será atribuido a 15 jogos aleatórios.
            // Atribui o ponto a vários jogos aleatório.
            $game = \Unscode\Pingo\Models\Game::all()->random(15);
            $player->games()->sync($game);
        });
    }
}
