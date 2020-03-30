<?php

namespace Unscode\Pingo\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'game_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //'user_id', 'game_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'game_id' => 'integer',
    ];

    /**
     * Obtém o usuário pertencente a este jogador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém todos os jogos pertencente a este ponto.
     */
    public function games()
    {
        return $this->morphedByMany(Game::class, 'playerable');
    }

    /**
     * Obtém as medalhas atribuidas a este jogador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function medals()
    {
        return $this->morphToMany(Medal::class, 'medallable');
    }

    /**
     * Obtém os pontos atribuidas a este usuário.
     * Atenção! Somente usuários jogadores podem ter pontos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function scores()
    {
        return $this->morphedByMany(Score::class, 'playerable');
    }
}
