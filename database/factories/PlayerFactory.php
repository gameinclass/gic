<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Player::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            // Para evitar colisão entre as colunas 'user_id' e 'game_id', é criado um novo usuário
            // O id desse usuário é então atribuído a coluna 'user_id' da jogador.
            $user = factory(App\Models\User::class)->create();
            // Para cada usuário é necessário um ator, então é também criado um ator para o usuário (player).
            $user->actor()->save(new \App\Models\Actor());
            // Retorna o id do usuário para ser atribuído a coluna.
            return $user->id;
        },
        'game_id' => $faker->randomElement(\App\Models\Game::pluck('id')->toArray()),
        'group_id' => function (array $player) {
            // Somente grupos que pertecem ao jogo !!!
            $plucked = \App\Models\Group::where('game_id', $player['game_id'])->pluck('id');
            if (!$plucked->isEmpty()) {
                return $plucked->random();
            }
            return null;
        },
    ];
});
