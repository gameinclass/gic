<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
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
