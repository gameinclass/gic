<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedalPolicy
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
}
