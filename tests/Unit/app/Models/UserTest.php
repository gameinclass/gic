<?php

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class UserTest extends TestCase
{
    /**
     * Os atributos que são atribuíveis em massa
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Os atributos excluídos do formulário JSON do modelo.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos em tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer'
    ];

    /**
     * Testa alguns atributos para configuração do model.
     *
     * @return void
     */
    public function test_attributes_configuration()
    {
        // Model
        $model = new User();
        // Assertions
        $this->assertEquals($this->fillable, $model->getFillable());
        $this->assertEquals($this->hidden, $model->getHidden());
        $this->assertEquals($this->casts, $model->getCasts());
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @return void
     */
    public function test_route_notification_for_slack()
    {
        // Model
        $model = new User();
        //
        $notification = new Notification();
        // Assertions
        $this->assertStringStartsWith('https://hooks.slack.com/services/', $model->routeNotificationForSlack($notification));
    }

    /**
     * Testa o relacionamento entre usuário e ator.
     *
     * @return void
     */
    public function test_has_one_actor_relation()
    {
        // Model
        $user = new User();
        // Assertions
        $this->assertInstanceOf(HasOne::class, $user->actor());
    }

    /**
     * Testa o relacionamento entre usuário e jogo.
     *
     * @return void
     */
    public function test_has_many_games_relation()
    {
        // Model
        $user = new User();
        // Assertions
        $this->assertInstanceOf(HasMany::class, $user->games());
    }

    /**
     * Testa o relacionamento entre usuário e jogador.
     *
     * @return void
     */
    public function test_has_many_players_relation()
    {
        // Model
        $user = new User();
        // Assertions
        $this->assertInstanceOf(HasMany::class, $user->players());
    }
}
