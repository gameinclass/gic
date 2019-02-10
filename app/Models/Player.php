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
     * Obtém todas as medalhas atribuida a esse jogador.
     */
    public function medals()
    {
        return $this->morphToMany(Medal::class, 'medallable');
    }

    /**
     * Obtém todas os pontos atribuído a esse jogador.
     */
    public function scores()
    {
        return $this->morphToMany(Score::class, 'scorable');
    }
}
