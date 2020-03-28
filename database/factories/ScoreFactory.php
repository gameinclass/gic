<?php

use Faker\Generator as Faker;

$factory->define(\Unscode\Pingo\Models\Score::class, function (Faker $faker) {
    return [
        'title' => $faker->text(),
        'value' => $faker->randomFloat(),
    ];
});
