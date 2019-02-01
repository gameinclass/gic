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
     * Obtém todas as medalhas atribuída a esse grupo.
     */
    public function medals()
    {
        return $this->morphToMany(Medal::class, 'medallable');
    }

    /**
     * Obtém todas os pontos atribuído a esse grupo.
     */
    public function scores()
    {
        return $this->morphToMany(Score::class, 'scorable');
    }
}
