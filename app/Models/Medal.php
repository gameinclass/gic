<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'path',
    ];

    /**
     * Obtém todos os jogadores atribuídos a essa medalha.
     */
    public function players()
    {
        return $this->morphedByMany(Player::class, 'medallable');
    }

    /**
     * Obtém todos os grupos atribuídos a essa medalha.
     */
    public function groups()
    {
        return $this->morphedByMany(Group::class, 'medallable');
    }
}