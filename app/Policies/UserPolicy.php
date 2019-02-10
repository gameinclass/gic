<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can index the model.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->actor && $user->actor->is_administrator;
    }

    /**
     * Determine whether the user can store models.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function store(User $user)
    {
        return $user->actor && $user->actor->is_administrator;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $u
     * @return mixed
     */
    public function update(User $user, User $u)
    {
        if (!$user->actor) {
            return false;
        }
        if ($user->actor->is_administrator) {
            return true;
        }
        return $user->id === $u->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $u
     * @return mixed
     */
    public function destroy(User $user, User $u)
    {
        if (!$user->actor) {
            return false;
        }
        if ($user->actor->is_administrator) {
            return true;
        }
        return $user->id === $u->id;
    }
}
