<?php

namespace Tests\Unit\app\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Actor;
use App\Models\Medal;
use App\Policies\MedalPolicy;

class MedalPolicyTest extends TestCase
{
    /**
     * Usuário administrador
     *
     * @return User
     */
    private function administrator()
    {
        $user = new User();
        $user->id = 1;
        $user->actor = new Actor();
        $user->actor->is_administrator = true;
        $user->actor->is_design = false;
        $user->actor->is_player = false;
        return $user;
    }

    /**
     * Usuário design
     *
     * @return User
     */
    private function design()
    {
        $user = new User();
        $user->id = 1;
        $user->actor = new Actor();
        $user->actor->is_administrator = false;
        $user->actor->is_design = true;
        $user->actor->is_player = false;
        return $user;
    }

    /**
     * Usuário jogador
     *
     * @return User
     */
    private function player()
    {
        $user = new User();
        $user->id = 1;
        $user->actor = new Actor();
        $user->actor->is_administrator = false;
        $user->actor->is_design = false;
        $user->actor->is_player = true;
        return $user;
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_index_policy_rules()
    {
        $policy = new MedalPolicy();
        $this->assertTrue($policy->index($this->administrator()));
        $this->assertTrue($policy->index($this->design()));
        $this->assertFalse($policy->index($this->player()));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_store_policy_rules()
    {
        $policy = new MedalPolicy();
        $this->assertTrue($policy->store($this->administrator()));
        $this->assertTrue($policy->store($this->design()));
        $this->assertFalse($policy->store($this->player()));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_update_policy_rules()
    {
        $policy = new MedalPolicy();
        // Uma medalha
        $medal = new Medal();

        $medal->user_id = 1;
        $this->assertTrue($policy->update($this->administrator(), $medal));
        $medal->user_id = 2;
        $this->assertTrue($policy->update($this->administrator(), $medal));

        $medal->user_id = 1;
        $this->assertTrue($policy->update($this->design(), $medal));
        $medal->user_id = 2;
        $this->assertFalse($policy->update($this->design(), $medal));

        $medal->user_id = 1;
        $this->assertFalse($policy->update($this->player(), $medal));
        $medal->user_id = 2;
        $this->assertFalse($policy->update($this->player(), $medal));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_destroy_policy_rules()
    {
        $policy = new MedalPolicy();
        // Uma medalha
        $medal = new Medal();

        $medal->user_id = 1;
        $this->assertTrue($policy->destroy($this->administrator(), $medal));
        $medal->user_id = 2;
        $this->assertTrue($policy->destroy($this->administrator(), $medal));

        $medal->user_id = 1;
        $this->assertTrue($policy->destroy($this->design(), $medal));
        $medal->user_id = 2;
        $this->assertFalse($policy->destroy($this->design(), $medal));

        $medal->user_id = 1;
        $this->assertFalse($policy->destroy($this->player(), $medal));
        $medal->user_id = 2;
        $this->assertFalse($policy->destroy($this->player(), $medal));
    }
}