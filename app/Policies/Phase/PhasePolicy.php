<?php

namespace App\Policies\Phase;

use App\Models\User;
use App\Models\Phase;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the phase.
     *
     * @param  \App\Models\Phase  $phase
     * @return mixed
     */
    public function index(User $user)
    {
        //
    }

    /**
     * Determine whether the user can create phases.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function store(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the phase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Phase  $phase
     * @return mixed
     */
    public function update(User $user, Phase $phase)
    {
        //
    }

    /**
     * Determine whether the user can delete the phase.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Phase  $phase
     * @return mixed
     */
    public function destroy(User $user, Phase $phase)
    {
        //
    }
}
