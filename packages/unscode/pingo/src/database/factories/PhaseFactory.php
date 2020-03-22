<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Phase::class, function (Faker $faker) {
    return [
        // Atenção! Já foi garantido que somente administrador e design podem criar jogos na fábrica de jogos.
        'game_id' => $faker->randomElement(\App\Models\Game::pluck('id')->toArray()),
        'from' => $faker->dateTimeBetween('now', '+5 days')->format('Y-m-d H:i:s'),
        'to' => $faker->dateTimeBetween('+5 days', '+60 days')->format('Y-m-d H:i:s'),
        'name' => $faker->text(),
    ];
});
