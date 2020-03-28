<?php

namespace Unscode\Pingo\Seeds;

use Illuminate\Database\Seeder;

class MedalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $d = Storage::disk('public')->deleteDirectory('medals');
        factory(\Unscode\Pingo\Models\Medal::class, 2999)->create()->each(function ($medal) {
            // Cada medalha criada será atribuida para um jogo específico.
            // Atribui a medalha a um jogo aleatório.
            $game = \Unscode\Pingo\Models\Game::all()->random(5);
            $medal->games()->sync($game);
        });
    }
}
