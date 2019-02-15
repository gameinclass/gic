<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Player::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            // Atenção! Each retorna Illuminate\Database\Eloquent\Collection
            $user = factory(\App\Models\User::class, 1)->create()->each(function ($user) {
                // Faker Factory
                $user->actor()->save(factory(\App\Models\Actor::class)->make([
                    // Atenção! Somente jogdores podem ser jogador.
                    'is_administrator' => false,
                    'is_design' => false,
                    'is_player' => true,
                ]));
            });
            return $user[0]->id;
        },
        'game_id' => \App\Models\Game::pluck('id')->random(),
    ];
});
