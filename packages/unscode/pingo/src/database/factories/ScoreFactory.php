<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Score::class, function (Faker $faker) {
    return [
        'title' => $faker->text(),
        'value' => $faker->randomFloat(),
    ];
});
