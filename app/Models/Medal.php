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
     * Obtém o usuário pertencente a este jogo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém todos os jogos pertencente a esta medalha.
     */
    public function games()
    {
        return $this->morphedByMany(Game::class, 'medallable');
    }

    /**
     * Obtém todos os grupos atribuídos a essa medalha.
     */
    public function groups()
    {
        return $this->morphedByMany(Group::class, 'medallable');
    }
}
