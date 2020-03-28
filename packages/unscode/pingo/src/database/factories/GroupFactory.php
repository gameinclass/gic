<?php

use Faker\Generator as Faker;

$factory->define(\Unscode\Pingo\Models\Group::class, function (Faker $faker) {
    return [
        'phase_id' => $faker->randomElement(\Unscode\Pingo\Models\Phase::pluck('id')->toArray()),
        'name' => $faker->text(),
    ];
});
