<?php

namespace Tests\Feature\app\Models;

use App\User;
use Tests\TestCase;
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
    protected $casts = [
        'id' => 'int',
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];
    protected $with = [
        'actor'
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
        $this->assertEquals($this->with, $model->getWith());
    }

    /**
     * Testa os relacionamentos do model.
     */
    public function test_has_one_actor_relation()
    {
        // Model
        $model = new User();
        // Assertions
        $this->assertInstanceOf(HasOne::class, $model->actor());
    }
}
