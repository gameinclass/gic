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
     * Obtém todas as medalhas do grupo.
     */
    public function medals()
    {
        return $this->morphToMany(Medal::class, 'taggable');
    }
}
