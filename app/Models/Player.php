<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * Obtém o usuário pertencente a este jogador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém o jogo pertencente a este jogador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Obtém os medalhas atribuidas a este usuário.
     * Atenção! Somente usuários jogadores podem ter medalhas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function medals()
    {
        return $this->morphedByMany(Medal::class, 'playerable');
    }

    /**
     * Obtém os pontos atribuidas a este usuário.
     * Atenção! Somente usuários jogadores podem ter pontos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function scores()
    {
        return $this->morphedByMany(Score::class, 'playerable');
    }
}
