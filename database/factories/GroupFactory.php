<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Group::class, function (Faker $faker) {
    return [
        'phase_id' => $faker->randomElement(\App\Models\Phase::pluck('id')->toArray()),
        'name' => $faker->text(),
    ];
});
