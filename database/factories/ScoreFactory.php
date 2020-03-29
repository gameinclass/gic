<?php

use Faker\Generator as Faker;

$factory->define(\Unscode\Pingo\Models\Score::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(1),
        'value' => $faker->randomFloat(),
    ];
});
