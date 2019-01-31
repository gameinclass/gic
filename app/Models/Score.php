<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    /**
     * Obtém todos os jogadores atribuídos a esse ponto.
     */
    public function players()
    {
        return $this->morphedByMany(Player::class, 'taggable');
    }

    /**
     * Obtém todos os grupos atribuídos a esse ponto.
     */
    public function groups()
    {
        return $this->morphedByMany(Group::class, 'taggable');
    }
}
