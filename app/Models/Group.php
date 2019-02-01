<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'game_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'medals'
    ];

    /**
     * Get the relations to eager load on every query.
     *
     * @return array
     */
    public function getWith()
    {
        return $this->with;
    }

    /**
     * Obtém o jogo pertencente a este grupo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Obtém todas as medalhas atribuída a este grupo.
     */
    public function medals()
    {
        return $this->morphToMany(Medal::class, 'medallable');
    }

    /**
     * Obtém todas os pontos atribuído a este grupo.
     */
    public function scores()
    {
        return $this->morphToMany(Score::class, 'scorable');
    }
}
