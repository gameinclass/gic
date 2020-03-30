<?php

namespace Unscode\Pingo\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer'
    ];

    /**
     * Route notifications for the Slack channel.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_WEBHOOKS', '');
    }

    /**
     * Obtém o ator atribuído a este usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function actor()
    {
        return $this->hasOne(Actor::class);
    }

    /**
     * Obtém os jogos atribuído a este usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function games()
    {
        return $this->hasMany(Game::class);
    }

    /**
     * Obtém os jodagores atribuídos a este usuário.
     * Atenção! Somente usuário jogadores podem ser adicionados.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function player()
    {
        return $this->hasOne(Player::class);
    }
}
