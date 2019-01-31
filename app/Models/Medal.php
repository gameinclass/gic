<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medal extends Model
{
    /**
     * Obtém todos os jogadores atribuídos a essa medalha.
     *
     */
    public function players()
    {
        return $this->morphedByMany(Player::class, 'taggable');
    }

    /**
     * Obtém todos os grupos atribuídos a essa medalha.
     */
    public function groups()
    {
        return $this->morphedByMany(Group::class, 'taggable');
    }
}
