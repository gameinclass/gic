<?php

namespace App\Policies\Game\Medal;

use App\Models\User;
use App\Models\Game;
use App\Models\Medal;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedalPolicy
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
        // Se o usuário for design, pode listar somente as medalhas dos seus jogos.
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
        // Se o usuário for design, pode adicionar medalhas somente aos seus jogos.
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
     * @param  \App\Models\Medal $medal
     * @return mixed
     */
    public function update(User $user, Game $game, Medal $medal)
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
            // 1 == "1" = true
            ($game->id == $medal->pivot->medallable_id);
    }

    /**
     * Determine whether the user can delete the phase.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Game $game
     * @param  \App\Models\Medal $medal
     * @return mixed
     */
    public function destroy(User $user, Game $game, Medal $medal)
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
            // 1 == "1" = true
            ($game->id == $medal->pivot->medallable_id);
    }
}
