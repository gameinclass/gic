<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Game;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the game.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        if (!$user->actor) {
            return false;
        }
        return $user->actor->is_administrator || $user->actor->is_design;
    }

    /**
     * Determine whether the user can create games.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function store(User $user)
    {
        if (!$user->actor) {
            return false;
        }
        return $user->actor->is_administrator || $user->actor->is_design;
    }

    /**
     * Determine whether the user can update the game.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Game $game
     * @return mixed
     */
    public function update(User $user, Game $game)
    {
        if (!$user->actor) {
            return false;
        }
        if ($user->actor->is_administrator) {
            return true;
        }
        return $user->actor->is_design && ($user->id == $game->user_id);
    }

    /**
     * Determine whether the user can delete the game.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Game $game
     * @return mixed
     */
    public function destroy(User $user, Game $game)
    {
        if (!$user->actor) {
            return false;
        }
        if ($user->actor->is_administrator) {
            return true;
        }
        return $user->actor->is_design && ($user->id == $game->user_id);
    }
}
