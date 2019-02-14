<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Phase::class, function (Faker $faker) {
    return [
        'game_id' => $faker->randomElement(\App\Models\Game::pluck('id')->toArray()),
        'name' => $faker->text(),
    ];
});