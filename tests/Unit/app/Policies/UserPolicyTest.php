<?php

namespace Tests\Unit\app\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Actor;
use App\Policies\UserPolicy;

class UserPolicyTest extends TestCase
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
        $policy = new UserPolicy();
        $this->assertTrue($policy->index($this->administrator()));
        $this->assertFalse($policy->index($this->design()));
        $this->assertFalse($policy->index($this->player()));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_store_policy_rules()
    {
        $policy = new UserPolicy();
        $this->assertTrue($policy->index($this->administrator()));
        $this->assertFalse($policy->index($this->design()));
        $this->assertFalse($policy->index($this->player()));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_update_policy_rules()
    {
        $policy = new UserPolicy();
       // Outro usuário
        $user = new User();
        $user->id = 2;

        $this->assertTrue($policy->update($this->administrator(), $this->administrator()));
        $this->assertTrue($policy->update($this->administrator(), $user));

        $this->assertTrue($policy->update($this->design(), $this->design()));
        $this->assertFalse($policy->update($this->design(), $user));

        $this->assertTrue($policy->update($this->player(), $this->player()));
        $this->assertFalse($policy->update($this->player(), $user));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_destroy_policy_rules()
    {
        $policy = new UserPolicy();
        // Outro usuário
        $user = new User();
        $user->id = 2;

        $this->assertTrue($policy->update($this->administrator(), $this->administrator()));
        $this->assertTrue($policy->update($this->administrator(), $user));

        $this->assertTrue($policy->update($this->design(), $this->design()));
        $this->assertFalse($policy->update($this->design(), $user));

        $this->assertTrue($policy->update($this->player(), $this->player()));
        $this->assertFalse($policy->update($this->player(), $user));
    }
}