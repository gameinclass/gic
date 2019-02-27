<?php

namespace App\Policies\Player;

use App\Models\User;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlayerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the player.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Game $game
     * @return mixed
     */
    public function index(User $user, Game $game)
    {
        if (!$user->actor) {
            return false;
        }
        // Se o usuário for administrador, pode fazer tudo !!!
        if ($user->actor->is_administrator) {
            return true;
        }
        // Se o usuário for design, pode criar fase para o jogo somente para o próprio jogo.
        if ($user->actor->is_design) {
            return $user->id === $game->user_id;
        }
        // Do contrário ...
        return false;
    }

    /**
     * Determine whether the user can create phases.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Game $game
     * @return mixed
     */
    public function store(User $user, Game $game)
    {
        if (!$user->actor) {
            return false;
        }
        // Se o usuário for administrador, pode fazer tudo !!!
        if ($user->actor->is_administrator) {
            return true;
        }
        // Se o usuário for design, pode criar fase para o jogo somente para o próprio jogo.
        if ($user->actor->is_design) {
            return $user->id === $game->user_id;
        }
        // Do contrário ...
        return false;
    }

    /**
     * Determine whether the user can update the phase.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Game $game
     * @param  \App\Models\Player $player
     * @return mixed
     */
    public function update(User $user, Game $game, Player $player)
    {
        if (!$user->actor) {
            return false;
        }
        // Se o usuário for administrador, pode fazer tudo !!!
        if ($user->actor->is_administrator) {
            return true;
        }
        return $user->actor->is_design &&
            ($user->id === $game->user_id) &&
            ($game->id === $player->game_id);
    }

    /**
     * Determine whether the user can delete the player.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Game $game
     * @param  \App\Models\Player $player
     * @return mixed
     */
    public function destroy(User $user, Game $game, Player $player)
    {
        if (!$user->actor) {
            return false;
        }
        // Se o usuário for administrador, pode fazer tudo !!!
        if ($user->actor->is_administrator) {
            return true;
        }
        return $user->actor->is_design &&
            ($user->id === $game->user_id) &&
            ($game->id === $player->game_id);
    }
}
