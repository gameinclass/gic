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

        $user = new User();
        $user->id = 1;
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

        $user = new User();
        $user->id = 1;
        $this->assertFalse($policy->store($user));

        $user->actor = new Actor(['is_administrator' => true, 'is_design' => false, 'is_player' => true]);
        $this->assertTrue($policy->store($user));

        $user->actor = new Actor(['is_administrator' => false, 'is_design' => true, 'is_player' => false]);
        $this->assertTrue($policy->store($user));

        $user->actor = new Actor(['is_administrator' => false, 'is_design' => false, 'is_player' => true]);
        $this->assertFalse($policy->store($user));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_update_policy_rules()
    {
        $policy = new GamePolicy();

        $user = new User();
        $user->id = 1;
        $game = new Game();
        $game->user_id = 1;
        $this->assertFalse($policy->update($user, $game));

        $user->actor = new Actor(['is_administrator' => true, 'is_design' => false, 'is_player' => true]);
        $game->user_id = 1;
        $this->assertTrue($policy->update($user, $game));
        $game->user_id = 2;
        $this->assertTrue($policy->update($user, $game));

        $user->actor = new Actor(['is_administrator' => false, 'is_design' => true, 'is_player' => false]);
        $game->user_id = 1;
        $this->assertTrue($policy->update($user, $game));
        $game->user_id = 2;
        $this->assertFalse($policy->update($user, $game));

        $user->actor = new Actor(['is_administrator' => false, 'is_design' => false, 'is_player' => true]);
        $game->user_id = 1;
        $this->assertFalse($policy->update($user, $game));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_destroy_policy_rules()
    {
        $policy = new GamePolicy();

        $user = new User();
        $user->id = 1;
        $game = new Game();
        $game->user_id = 1;
        $this->assertFalse($policy->destroy($user, $game));

        $user->actor = new Actor(['is_administrator' => true, 'is_design' => false, 'is_player' => true]);
        $game->user_id = 1;
        $this->assertTrue($policy->destroy($user, $game));
        $game->user_id = 2;
        $this->assertTrue($policy->destroy($user, $game));


        $user->actor = new Actor(['is_administrator' => false, 'is_design' => true, 'is_player' => false]);
        $game->user_id = 1;
        $this->assertTrue($policy->destroy($user, $game));
        $game->user_id = 2;
        $this->assertFalse($policy->destroy($user, $game));

        $user->actor = new Actor(['is_administrator' => false, 'is_design' => false, 'is_player' => true]);
        $game->user_id = 1;
        $this->assertFalse($policy->destroy($user, $game));
    }
}
