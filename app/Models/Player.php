<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * Obtém todas as medalhas do jogador.
     */
    public function medals()
    {
        return $this->morphToMany(Medal::class, 'taggable');
    }
}
