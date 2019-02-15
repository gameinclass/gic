<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id'
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
     * Obtém as medalhas pertencente a este jogo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function medals()
    {
        return $this->morphToMany(Medal::class, 'medallable');
    }

    /**
     * Obtém os pontos pertencente a este jogo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function scores()
    {
        return $this->morphToMany(Score::class, 'scorable');
    }

    /**
     * Obtém os grupos pertencente a este jogo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Obtém os jogadores pertencente a este jogo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
