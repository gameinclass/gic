<?php

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
        // Remove o diretório de imagens de medalhas do disco.
        $deleted = Storage::disk('public')->deleteDirectory(md5('medals'));

        factory(\Unscode\Pingo\Models\Medal::class, 100)->create()->each(function ($medal) {
            // Cada medalha criada será atribuida para um jogo específico.
            // Atribui a medalha a 5 jogo aleatório.
            $game = \Unscode\Pingo\Models\Game::all()->random(5);
            $medal->games()->sync($game);
        });
    }
}
