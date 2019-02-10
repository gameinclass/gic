<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Actor::class, function (Faker $faker) {
    $boolean = $faker->boolean;
    return [
        'is_administrator' => $faker->boolean,
        'is_design' => $boolean,
        'is_player' => !$boolean,
    ];
});
