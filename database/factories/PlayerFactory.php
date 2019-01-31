<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Player::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            // Para evitar colisão entre as colunas 'user_id' e 'game_id', é criado um novo usuário
            // O id desse usuário é então atribuído a coluna 'user_id' da jogador.
            $user = factory(App\User::class)->create();
            // Para cada usuário é necessário um ator, então é também criado um ator para o usuário (player).
            $user->actor()->save(new \App\Models\Actor());
            // Retorna o id do usuário para ser atribuído a coluna.
            return $user->id;
        },
        'game_id' => $faker->randomElement(\App\Models\Game::pluck('id')->toArray()),
    ];
});
