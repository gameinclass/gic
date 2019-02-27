<?php

namespace App\Policies\Phase;

use App\Models\Game;
use App\Models\User;
use App\Models\Phase;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the phase.
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
     * @param  \App\Models\Phase $phase
     * @return mixed
     */
    public function update(User $user, Game $game, Phase $phase)
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
            ($game->id === $phase->game_id);
    }

    /**
     * Determine whether the user can delete the phase.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Game $game
     * @param  \App\Models\Phase $phase
     * @return mixed
     */
    public function destroy(User $user, Game $game, Phase $phase)
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
            ($game->id === $phase->game_id);
    }
}
