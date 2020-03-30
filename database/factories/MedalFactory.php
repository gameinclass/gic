<?php

use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(\Unscode\Pingo\Models\Medal::class, function (Faker $faker) {
    // Arquivo de imagem falsa com dimensão e tamanho aleatório.
    $uploaded = UploadedFile::fake()
        ->image('image.jpg', rand(100, 150), rand(100, 800))
        ->size(rand(100, 150));

    // Para cada medalha é criado um usuário administrador ou design, somente esses usuários podem
    // criar medalhas.
    // Atenção! each retorna Illuminate\Database\Eloquent\Collection
    $user = factory(\App\Models\User::class, 1)->create()->each(function ($user) {
        // Faker Factory
        $boolean = \Faker\Factory::create()->boolean;
        $user->actor()->save(factory(\App\Models\Actor::class)->make([
            // Atenção! Somente o administrador ou o design pode criar medalhas.
            'is_administrator' => $boolean,
            'is_design' => !$boolean,
            'is_player' => false,
        ]));
    });

    // Salva a imagem no disco.
    $path = $uploaded->store(md5('medals') . '/' . md5($user[0]->id), 'public');

    return [
        'user_id' => $user[0]->id,
        'title' => $faker->sentence(2),
        'description' => $faker->randomHtml(),
        'path' => $path
    ];
});
