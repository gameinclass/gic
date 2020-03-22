<?php

namespace Unscode\Pingo\Models;

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
     * Obtém todas as medalhas atribuída a este grupo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function medals()
    {
        return $this->morphToMany(Medal::class, 'medallable');
    }

    /**
     * Obtém todas os pontos atribuído a este grupo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function scores()
    {
        return $this->morphToMany(Score::class, 'scorable');
    }
}
