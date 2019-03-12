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
     * Obtém o usuário atribuído a esta medalha.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém todos os jogos atribuídos a esta medalha.
     */
    public function games()
    {
        return $this->morphedByMany(Game::class, 'medallable');
    }

    /**
     * Obtém todos os jogadores atribuídos a esta medalha.
     */
    public function players()
    {
        return $this->morphedByMany(Player::class, 'medallable');
    }

    /**
     * Obtém todos os grupos atribuídos a esta medalha.
     */
    public function groups()
    {
        return $this->morphedByMany(Group::class, 'medallable');
    }
}
