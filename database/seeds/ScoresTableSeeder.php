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
        factory(\App\Models\Score::class, 800)->create()->each(function ($score) {
            // Cada ponto será atribuído a um jogador ou grupo.
            if (rand(0, 1)) {
                // Atribui a um jogador aleatório.
                $player = \App\Models\Player::all()->random();
                $score->players()->sync($player);
            } else {
                // Atribui a um grupo aleatório.
                $group = \App\Models\Group::all()->random();
                $score->groups()->sync($group);
            }
        });
    }
}
