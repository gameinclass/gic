<?php

namespace App\Policies\User\Actor;

use App\Models\User;
use App\Models\Actor;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the game.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Actor $actor
     * @return mixed
     */
    public function update(User $user, Actor $actor)
    {
        if (!$user->actor) {
            return false;
        }
        if ($user->actor->is_administrator) {
            return true;
        }
        return false;
    }
}
