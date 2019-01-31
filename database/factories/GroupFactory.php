<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Group::class, function (Faker $faker) {
    $games = \App\Models\Game::pluck('id')->toArray();
    return [
        'game_id' => $faker->randomElement($games),
        'name' => $faker->text(),
    ];
});
