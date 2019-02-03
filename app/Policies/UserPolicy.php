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
     * @param  \App\User $model
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->actor && $user->actor->is_administrator;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\User $model
     * @return mixed
     */
    public function destroy(User $user)
    {
        return $user->actor && $user->actor->is_administrator;
    }
}
