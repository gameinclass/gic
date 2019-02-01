<?php

namespace Tests\Feature\app\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
     */
    public function test_has_one_actor_relation()
    {
        // Model
        $user = new User();
        // Assertions
        $this->assertInstanceOf(HasOne::class, $user->actor());
    }

    /**
     * Testa o relacionamento entre usuário e jogos.
     */
    public function test_has_many_games_relation()
    {
        // Model
        $user = new User();
        // Assertions
        $this->assertInstanceOf(HasMany::class, $user->games());
    }
}
