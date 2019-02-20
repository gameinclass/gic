<?php

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class UserTest extends TestCase
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
