<?php
/**
 * Created by PhpStorm.
 * User: jorge
 * Date: 22/01/19
 * Time: 11:25
 */

namespace Tests\Unit\App\Models;

use App\User;
use App\Models\Actor;
use Tests\ModelTestCase;
use Illuminate\Database\Eloquent\Collection;

class ActorTest extends ModelTestCase
{
    protected $fillable = ['is_administrator', 'is_design', 'is_player'];
    protected $hidden = [];
    protected $guarded = ['*'];
    protected $visible = [];
    protected $casts = ['id' => 'int'];
    protected $dates = [];
    protected $collectionClass = Collection::class;
    protected $table = null;
    protected $primaryKey = 'id';
    protected $connection = null;

    /**
     * Test
     *
     * @return void
     */
    public function test_actor_configuration() // phpcs:disable
    {
        $this->runConfigurationAssertions(
            new  Actor(),
            $this->fillable,
            $this->hidden,
            $this->guarded,
            $this->visible,
            $this->casts,
            $this->dates,
            $this->collectionClass,
            $this->table,
            $this->primaryKey,
            $this->connection
        );
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
        $this->assertIsBool($m->getIsAdministratorAttribute('1'));
        $this->assertIsBool($m->getIsDesignAttribute('1'));
        $this->assertIsBool($m->getIsPlayerAttribute('1'));
        // Verifica o valor de retorno do método
        $this->assertNotTrue($m->getIsAdministratorAttribute('0'));
        $this->assertNotTrue($m->getIsDesignAttribute('0'));
        $this->assertNotFalse($m->getIsPlayerAttribute('1'));
    }
}
