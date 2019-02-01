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
        $d = Storage::disk('public')->deleteDirectory('medals');
        factory(\App\Models\Medal::class, 50)->create()->each(function ($medal) {
            // Cada medalha será atribuído a um jogador ou grupo.
            if (rand(0, 1)) {
                // Atribui a um jogador aleatório.
                $player = \App\Models\Player::all()->random();
                $medal->players()->sync($player);
            } else {
                // Atribui a um grupo aleatório.
                $group = \App\Models\Group::all()->random();
                $medal->groups()->sync($group);
            }
        });
    }
}
