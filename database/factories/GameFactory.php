<?php

use Faker\Generator as Faker;

$factory->define(\Unscode\Pingo\Models\Game::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            // Atenção! Each retorna Illuminate\Database\Eloquent\Collection
            $user = factory(\App\Models\User::class, 1)->create()->each(function ($user) {
                // Faker Factory
                $boolean = \Faker\Factory::create()->boolean;
                $user->actor()->save(factory(\App\Models\Actor::class)->make([
                    // Atenção! Somente administrador e design pode criar jogo.
                    'is_administrator' => $boolean,
                    'is_design' => !$boolean,
                    'is_player' => false,
                ]));
            });
            return $user[0]->id;
        },
        'title' => $faker->sentence(4),
        'description' => $faker->text(),
    ];
});
