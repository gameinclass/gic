<?php

namespace Tests\Unit\App\Models;

use App\User;
use App\Models\Actor;
use Tests\ModelTestCase;
use Illuminate\Database\Eloquent\Collection;

class UserTest extends ModelTestCase
{
    protected $fillable = ['name', 'email', 'email_verified_at', 'password', 'remember_token'];
    protected $hidden = [];
    protected $guarded = ['*'];
    protected $visible = [];
    protected $casts = ['id' => 'int'];
    protected $dates = ['created_at', 'updated_at'];
    protected $collectionClass = Collection::class;
    protected $table = null;
    protected $primaryKey = 'id';
    protected $connection = null;

    /**
     * Test
     *
     * @return void
     */
    public function test_user_configuration() // phpcs:disable
    {
        $this->runConfigurationAssertions(
            new  User(),
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
    public function test_user_relations() // phpcs:disable
    {
        $m = new User();
        $this->assertHasOneRelation($m->actor(), $m, new Actor(), 'user_id');
    }
}
