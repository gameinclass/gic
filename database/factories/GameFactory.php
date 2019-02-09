<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Game::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(\App\Models\User::pluck('id')->toArray()),
        'title' => $faker->text(),
        'description' => $faker->text(),
    ];
});
