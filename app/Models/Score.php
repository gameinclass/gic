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
     * Obtém todos os jogadores atribuídos a esse ponto.
     */
    public function players()
    {
        return $this->morphedByMany(Player::class, 'scorable');
    }

    /**
     * Obtém todos os grupos atribuídos a esse ponto.
     */
    public function groups()
    {
        return $this->morphedByMany(Group::class, 'scorable');
    }
}
