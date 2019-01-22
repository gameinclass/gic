<?php
/**
 * Created by PhpStorm.
 * User: jorge
 * Date: 22/01/19
 * Time: 11:25
 */

namespace Tests\Unit\App\Models;

use App\Models\Actor;
use App\User;
use Tests\ModelTestCase;

class ActorTest extends ModelTestCase
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_administrator', 'is_design', 'is_player',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
    ];

    /**
     * Test
     *
     * @return void
     */
    public function test_actor_configuration() // phpcs:disable
    {
        $this->runConfigurationAssertions(new  Actor(), $this->fillable, $this->hidden);
    }

    /**
     * Test
     *
     * @return void
     */
    public function test_actor_relations() // phpcs:disable
    {
        $m = new Actor();
        $this->assertBelongsToRelation($m->user(), $m, new User(), 'user_id');
    }

    /**
     * Test
     *
     * @return void
     */
    public function test_actor_mutators() // phpcs:disable
    {
        $m = new Actor();
        // Verifica o tipo de retorno do método
        $this->assertIsBool($m->getIsAdministrorAttribute('1'));
        $this->assertIsBool($m->getIsDesignAttribute('1'));
        $this->assertIsBool($m->getIsPlayerAttribute('1'));
        // Verifica o valor de retorno do método
        $this->assertNotTrue($m->getIsAdministrorAttribute('0'));
        $this->assertNotTrue($m->getIsDesignAttribute('0'));
        $this->assertNotFalse($m->getIsPlayerAttribute('1'));
    }
}
