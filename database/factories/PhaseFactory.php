<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Phase::class, function (Faker $faker) {
    return [
        // Atenção! Já foi garantido que somente administrador e design podem criar jogos na fábrica de jogos.
        'game_id' => $faker->randomElement(\App\Models\Game::pluck('id')->toArray()),
        'name' => $faker->text(),
    ];
});
