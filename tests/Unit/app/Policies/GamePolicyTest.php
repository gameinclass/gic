<?php

namespace Tests\Unit\app\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Actor;
use App\Policies\GamePolicy;

class GamePolicyTest extends TestCase
{
    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_index_policy_rules()
    {
        $policy = new GamePolicy();

        $user = new User(['id' => 1, 'name' => 'Test', 'email' => 'email@email.com', 'senha' => 'secret']);
        $this->assertFalse($policy->index($user));

        $user->actor = new Actor(['is_administrator' => true, 'is_design' => false, 'is_player' => true]);
        $this->assertTrue($policy->index($user));

        $user->actor = new Actor(['is_administrator' => false, 'is_design' => true, 'is_player' => false]);
        $this->assertTrue($policy->index($user));

        $user->actor = new Actor(['is_administrator' => false, 'is_design' => false, 'is_player' => true]);
        $this->assertFalse($policy->index($user));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_store_policy_rules()
    {
        $policy = new GamePolicy();

        $user = new User(['id' => 1, 'name' => 'Test', 'email' => 'email@email.com', 'senha' => 'secret']);
        $this->assertFalse($policy->store($user));

        $user->actor = new Actor(['is_administrator' => true, 'is_design' => false, 'is_player' => true]);
        $this->assertTrue($policy->store($user));

        $user->actor = new Actor(['is_administrator' => false, 'is_design' => true, 'is_player' => false]);
        $this->assertTrue($policy->store($user));

        $user->actor = new Actor(['is_administrator' => false, 'is_design' => false, 'is_player' => true]);
        $this->assertFalse($policy->store($user));
    }
}
