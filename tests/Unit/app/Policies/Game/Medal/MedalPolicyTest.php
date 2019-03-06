<?php

namespace Tests\Unit\app\Policies\Game\Medal;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Medal;
use App\Models\Actor;
use App\Policies\Game\Medal\MedalPolicy;

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
        // Atenção! Para os testes, todos os usuário possuem id = 1.
        // Jogo pertence ao próprio usuário
        $gameOne = new Game();
        $gameOne->user_id = 1;
        // Jogo pertencente a outro usuário
        $gameTwo = new Game();
        $gameTwo->user_id = 2;

        // Administrador
        $this->assertTrue($policy->index($this->administrator(), $gameOne));
        $this->assertTrue($policy->index($this->administrator(), $gameTwo));
        // Design
        $this->assertTrue($policy->index($this->design(), $gameOne));
        $this->assertFalse($policy->index($this->design(), $gameTwo));
        // Jogador
        $this->assertFalse($policy->index($this->player(), $gameOne));
        $this->assertFalse($policy->index($this->player(), $gameTwo));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_store_policy_rules()
    {
        $policy = new MedalPolicy();
        // Atenção! Para os testes, todos os usuário possuem id = 1.
        // Jogo pertence ao próprio usuário
        $gameOne = new Game();
        $gameOne->user_id = 1;
        // Jogo pertencente a outro usuário
        $gameTwo = new Game();
        $gameTwo->user_id = 2;

        // Administrador
        $this->assertTrue($policy->store($this->administrator(), $gameOne));
        $this->assertTrue($policy->store($this->administrator(), $gameTwo));
        // Design
        $this->assertTrue($policy->store($this->design(), $gameOne));
        $this->assertFalse($policy->store($this->design(), $gameTwo));
        // Jogador
        $this->assertFalse($policy->store($this->player(), $gameOne));
        $this->assertFalse($policy->store($this->player(), $gameTwo));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_update_policy_rules()
    {
        $policy = new MedalPolicy();
        // Atenção! Para os testes, todos os usuário possuem id = 1.
        // Jogo pertence ao próprio usuário
        $gameOne = new Game();
        $gameOne->id = 1;
        $gameOne->user_id = 1;
        // Medalha vinculada a primeiro jogo.
        $gameOneMedalOne = new Medal();
        $gameOneMedalOne->id = 1;
        $gameOneMedalOne->setAttribute('pivot', (object)[
            'medal_id' => $gameOneMedalOne->id,
            'medallable_id' => $gameOne->id,
            'medallable_type' => 'App\\Models\\Game'
        ]);
        // Jogo pertencente a outro usuário
        $gameTwo = new Game();
        $gameTwo->id = 2;
        $gameTwo->user_id = 2;
        // Medalha vinculada a segundo jogo.
        $gameTwoMedalOne = new Medal();
        $gameTwoMedalOne->id = 1;
        $gameTwoMedalOne->setAttribute('pivot', (object)[
            'medal_id' => $gameTwoMedalOne->id,
            'medallable_id' => $gameTwo->id,
            'medallable_type' => 'App\\Models\\Game'
        ]);

        // Jogo deste usuário e medalha deste jogo
        $this->assertTrue($policy->update($this->administrator(), $gameOne, $gameOneMedalOne));
        // Jogo deste usuário e medalha de outro jogo
        // (404) $this->assertTrue($policy->update($this->administrator(), $gameOne, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha do jogo
        $this->assertTrue($policy->update($this->administrator(), $gameTwo, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha de outro jogo
        // (404) $this->assertTrue($policy->update($this->administrator(), $gameTwo, $gameOneMedalOne));

        // Jogo deste usuário e medalha deste jogo
        $this->assertTrue($policy->update($this->design(), $gameOne, $gameOneMedalOne));
        // Jogo deste usuário e medalha de outro jogo
        // (404) $this->assertFalse($policy->update($this->design(), $gameOne, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha do jogo
        $this->assertFalse($policy->update($this->design(), $gameTwo, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha de outro jogo
        // (404) $this->assertFalse($policy->update($this->design(), $gameTwo, $gameOneMedalOne));


        // Atenção, este ator não cria jogo.
        // Jogo de outro usuário e medalha deste jogo
        $this->assertFalse($policy->update($this->player(), $gameOne, $gameOneMedalOne));
        // Jogo de outro  usuário e medalha de outro jogo
        // (404) $this->assertFalse($policy->update($this->player(), $gameOne, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha do jogo
        $this->assertFalse($policy->update($this->player(), $gameTwo, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha de outro jogo
        // (404) $this->assertFalse($policy->update($this->player(), $gameTwo, $gameOneMedalOne));
    }

    /**
     * Testa a lógica do policiamento
     *
     * @return void
     */
    public function test_destroy_policy_rules()
    {
        $policy = new MedalPolicy();
        // Atenção! Para os testes, todos os usuário possuem id = 1.
        // Jogo pertence ao próprio usuário
        $gameOne = new Game();
        $gameOne->id = 1;
        $gameOne->user_id = 1;
        // Medalha vinculada a primeiro jogo.
        $gameOneMedalOne = new Medal();
        $gameOneMedalOne->id = 1;
        $gameOneMedalOne->setAttribute('pivot', (object)[
            'medal_id' => $gameOneMedalOne->id,
            'medallable_id' => $gameOne->id,
            'medallable_type' => 'App\\Models\\Game'
        ]);
        // Jogo pertencente a outro usuário
        $gameTwo = new Game();
        $gameTwo->id = 2;
        $gameTwo->user_id = 2;
        // Medalha vinculada a segundo jogo.
        $gameTwoMedalOne = new Medal();
        $gameTwoMedalOne->id = 1;
        $gameTwoMedalOne->setAttribute('pivot', (object)[
            'medal_id' => $gameTwoMedalOne->id,
            'medallable_id' => $gameTwo->id,
            'medallable_type' => 'App\\Models\\Game'
        ]);

        // Jogo deste usuário e medalha deste jogo
        $this->assertTrue($policy->destroy($this->administrator(), $gameOne, $gameOneMedalOne));
        // Jogo deste usuário e medalha de outro jogo
        // (404) $this->assertTrue($policy->destroy($this->administrator(), $gameOne, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha do jogo
        $this->assertTrue($policy->destroy($this->administrator(), $gameTwo, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha de outro jogo
        // (404) $this->assertTrue($policy->destroy($this->administrator(), $gameTwo, $gameOneMedalOne));

        // Jogo deste usuário e medalha deste jogo
        $this->assertTrue($policy->destroy($this->design(), $gameOne, $gameOneMedalOne));
        // Jogo deste usuário e medalha de outro jogo
        // (404) $this->assertFalse($policy->destroy($this->design(), $gameOne, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha do jogo
        $this->assertFalse($policy->destroy($this->design(), $gameTwo, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha de outro jogo
        // (404) $this->assertFalse($policy->destroy($this->design(), $gameTwo, $gameOneMedalOne));


        // Atenção, este ator não cria jogo.
        // Jogo de outro usuário e medalha deste jogo
        $this->assertFalse($policy->destroy($this->player(), $gameOne, $gameOneMedalOne));
        // Jogo de outro  usuário e medalha de outro jogo
        // (404) $this->assertFalse($policy->destroy($this->player(), $gameOne, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha do jogo
        $this->assertFalse($policy->destroy($this->player(), $gameTwo, $gameTwoMedalOne));
        // Jogo de outro usuário com medalha de outro jogo
        // (404) $this->assertFalse($policy->destroy($this->player(), $gameTwo, $gameOneMedalOne));
    }
}