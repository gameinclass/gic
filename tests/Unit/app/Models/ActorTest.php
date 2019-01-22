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
    public function test_model_configuration() // phpcs:disable
    {
        $this->runConfigurationAssertions(new  Actor(), $this->fillable, $this->hidden);
    }

    /**
     * Test
     *
     * @return void
     */
    public function test_user_relation() // phpcs:disable
    {
        $m = new Actor();
        $r = $m->user();
        $this->assertBelongsToRelation($r, $m, new User(), 'user_id');
    }
}
