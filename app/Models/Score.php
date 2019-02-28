<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'value'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer'
    ];

    /**
     * Obtém todos os jogos pertencente a este ponto.
     */
    public function games()
    {
        return $this->morphedByMany(Game::class, 'scorable');
    }

    /**
     * Obtém todos os grupos atribuídos a este ponto.
     */
    public function groups()
    {
        return $this->morphedByMany(Group::class, 'scorable');
    }

    /**
     * Obtém todos os jogadores atribuídos a este ponto.
     */
    public function players()
    {
        return $this->morphedByMany(Player::class, 'scorable');
    }
}
