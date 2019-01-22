<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_administrator', 'is_design', 'is_player',
    ];

    /**
     * Get the actor's user id.
     *
     * @param  string $value
     * @return int
     */
    public function getUserIdAttribute($value)
    {
        return (int)$value;
    }

    /**
     * Get the actor's is administrator.
     *
     * @param  string $value
     * @return bool
     */
    public function getIsAdministrorAttribute($value)
    {
        return (bool)$value;
    }

    /**
     * Get the actor's is quest.
     *
     * @param  string $value
     * @return bool
     */
    public function getIsDesignAttribute($value)
    {
        return (bool)$value;
    }

    /**
     * Get the actor's is administrator.
     *
     * @param  string $value
     * @return bool
     */
    public function getIsPlayerAttribute($value)
    {
        return (bool)$value;
    }

    /**
     * Obter o usuário do ator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
