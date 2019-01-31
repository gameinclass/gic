<?php

use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(\App\Models\Medal::class, function (Faker $faker) {

    // Arquivo de imagem falsa com dimensão e tamanho aleatório.
    $uploaded = UploadedFile::fake()->image('image.jpg', rand(100, 800), rand(100, 800))->size(rand(100, 800));

    // Salva a imagem no disco.
    $path = $uploaded->store('medals', 'public');

    return [
        'title' => $faker->text(),
        'description' => $faker->randomHtml(),
        'path' => $path
    ];
});
