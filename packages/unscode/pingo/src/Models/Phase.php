<?php

namespace Unscode\Pingo\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'from', 'to',
    ];

    /**
     * The attributes excluded from the model's JSON form.
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
        'user_id' => 'integer',
        'from' => 'datetime:Y-m-d H:i:s',
        'to' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Set the phase's to.
     *
     * @param  string $value
     * @return void
     */
    public function setToAttribute($value)
    {
        $this->attributes['to'] = $value;

        // Verifica se a data de inicio é menor ou igual a data de término.
        if ($this->from->greaterThanOrEqualTo($value)) {
            // Se sim, adiciona a mesma data de termino.
            $this->attributes['to'] = Carbon::createFromFormat('d/m/Y H:i', $this->from)
                ->format('Y-m-d H:i:s');
        }
    }

    /**
     * Obtém o jogo pertencente a esta fase.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
