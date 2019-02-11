<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Medal;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the medal.
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
     * Determine whether the user can create medals.
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
     * Determine whether the user can update the medal.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Medal $medal
     * @return mixed
     */
    public function update(User $user, Medal $medal)
    {
        if (!$user->actor) {
            return false;
        }
        if ($user->actor->is_administrator) {
            return true;
        }
        return $user->actor->is_design && ($user->id == $medal->user_id);
    }

    /**
     * Determine whether the user can delete the medal.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Medal $medal
     * @return mixed
     */
    public function destroy(User $user, Medal $medal)
    {
        if (!$user->actor) {
            return false;
        }
        if ($user->actor->is_administrator) {
            return true;
        }
        return $user->actor->is_design && ($user->id == $medal->user_id);
    }
}
