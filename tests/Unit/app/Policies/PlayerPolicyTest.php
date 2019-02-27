<?php

namespace Tests\Unit\app\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Actor;
use App\Models\Player;
use App\Policies\Player\PlayerPolicy;

class PlayerPolicyTest extends TestCase
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
        $policy = new PlayerPolicy();
        // Um jogo
        $game = new Game();

        $game->user_id = 1; // Jogo do usuário
        $this->assertTrue($policy->index($this->administrator(), $game));
        $game->user_id = 2; // Jogo de outro usuário
        $this->assertTrue($policy->index($this->administrator(), $game));

        $game->user_id = 1; // Jogo do usuário
        $this->assertTrue($policy->index($this->design(), $game));
        $game->user_id = 2; // Jogo de outro usuário
        $this->assertFalse($policy->index($this->design(), $game));

        $game->user_id = 1; // Jogo do usuário
        $this->assertFalse($policy->index($this->player(), $game));
        $game->user_id = 2; // Jogo de outro usuário
        $this->assertFalse($policy->index($this->player(), $game));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_store_policy_rules()
    {
        $policy = new PlayerPolicy();
        // Um jogo
        $game = new Game();

        $game->user_id = 1; // Jogo do usuário
        $this->assertTrue($policy->store($this->administrator(), $game));
        $game->user_id = 2; // Jogo de outro usuário
        $this->assertTrue($policy->store($this->administrator(), $game));

        $game->user_id = 1; // Jogo do usuário
        $this->assertTrue($policy->store($this->design(), $game));
        $game->user_id = 2; // Jogo de outro usuário
        $this->assertFalse($policy->store($this->design(), $game));

        $game->user_id = 1; // Jogo do usuário
        $this->assertFalse($policy->store($this->player(), $game));
        $game->user_id = 2; // Jogo de outro usuário
        $this->assertFalse($policy->store($this->player(), $game));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_update_policy_rules()
    {
        $policy = new PlayerPolicy();
        // Um jogo
        $game = new Game();
        $game->id = 1;
        // Uma fase
        $player = new Player();

        $game->user_id = 1; // Jogo do usuário
        $player->game_id = 1; // Uma fase do jogo
        $this->assertTrue($policy->update($this->administrator(), $game, $player));
        $player->game_id = 2; // Uma fase de outro jogo
        $this->assertTrue($policy->update($this->administrator(), $game, $player));
        $game->user_id = 2; // Jogo de outro usuário
        $player->game_id = 2; // Uma fase do jogo
        $this->assertTrue($policy->update($this->administrator(), $game, $player));
        $player->game_id = 3; // Uma fase de outro jogo
        $this->assertTrue($policy->update($this->administrator(), $game, $player));

        $game->user_id = 1; // Jogo do usuário
        $player->game_id = 1; // Uma fase do jogo
        $this->assertTrue($policy->update($this->design(), $game, $player));
        $player->game_id = 2; // Uma fase de outro jogo
        $this->assertFalse($policy->update($this->design(), $game, $player));
        $game->user_id = 2; // Jogo de outro usuário
        $player->game_id = 2; // Uma fase do jogo
        $this->assertFalse($policy->update($this->design(), $game, $player));
        $player->game_id = 3; // Uma fase de outro jogo
        $this->assertFalse($policy->update($this->design(), $game, $player));


        $game->user_id = 1; // Jogo do usuário
        $player->game_id = 1; // Uma fase do jogo
        $this->assertFalse($policy->update($this->player(), $game, $player));
        $player->game_id = 2; // Uma fase de outro jogo
        $this->assertFalse($policy->update($this->player(), $game, $player));
        $game->user_id = 2; // Jogo de outro usuário
        $player->game_id = 2; // Uma fase do jogo
        $this->assertFalse($policy->update($this->player(), $game, $player));
        $player->game_id = 3; // Uma fase de outro jogo
        $this->assertFalse($policy->update($this->player(), $game, $player));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_destroy_policy_rules()
    {
        $policy = new PlayerPolicy();
        // Um jogo
        $game = new Game();
        $game->id = 1;
        // Uma fase
        $player = new Player();

        $game->user_id = 1; // Jogo do usuário
        $player->game_id = 1; // Uma fase do jogo
        $this->assertTrue($policy->destroy($this->administrator(), $game, $player));
        $player->game_id = 2; // Uma fase de outro jogo
        $this->assertTrue($policy->destroy($this->administrator(), $game, $player));
        $game->user_id = 2; // Jogo de outro usuário
        $player->game_id = 2; // Uma fase do jogo
        $this->assertTrue($policy->destroy($this->administrator(), $game, $player));
        $player->game_id = 3; // Uma fase de outro jogo
        $this->assertTrue($policy->destroy($this->administrator(), $game, $player));

        $game->user_id = 1; // Jogo do usuário
        $player->game_id = 1; // Uma fase do jogo
        $this->assertTrue($policy->destroy($this->design(), $game, $player));
        $player->game_id = 2; // Uma fase de outro jogo
        $this->assertFalse($policy->destroy($this->design(), $game, $player));
        $game->user_id = 2; // Jogo de outro usuário
        $player->game_id = 2; // Uma fase do jogo
        $this->assertFalse($policy->destroy($this->design(), $game, $player));
        $player->game_id = 3; // Uma fase de outro jogo
        $this->assertFalse($policy->destroy($this->design(), $game, $player));


        $game->user_id = 1; // Jogo do usuário
        $player->game_id = 1; // Uma fase do jogo
        $this->assertFalse($policy->update($this->player(), $game, $player));
        $player->game_id = 2; // Uma fase de outro jogo
        $this->assertFalse($policy->update($this->player(), $game, $player));
        $game->user_id = 2; // Jogo de outro usuário
        $player->game_id = 2; // Uma fase do jogo
        $this->assertFalse($policy->update($this->player(), $game, $player));
        $player->game_id = 3; // Uma fase de outro jogo
        $this->assertFalse($policy->update($this->player(), $game, $player));
    }
}