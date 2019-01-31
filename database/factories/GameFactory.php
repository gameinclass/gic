<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Game::class, function (Faker $faker) {
    $users = \App\User::pluck('id')->toArray();
    return [
        'user_id' => $faker->randomElement($users),
        'title' => $faker->text(),
        'description' => $faker->randomHtml(),
    ];
});
