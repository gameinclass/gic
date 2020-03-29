<?php

use Faker\Generator as Faker;

$factory->define(\Unscode\Pingo\Models\Phase::class, function (Faker $faker) {
    return [
        // Atenção! Já foi garantido que somente administrador e design podem criar jogos na fábrica de jogos.
        'game_id' => $faker->randomElement(\Unscode\Pingo\Models\Game::pluck('id')->toArray()),
        'title' => $faker->sentence(2),
        'start' => $faker->dateTimeBetween('now', '+5 days')->format('Y-m-d H:i:s'),
        'finish' => $faker->dateTimeBetween('+5 days', '+60 days')->format('Y-m-d H:i:s')
    ];
});
