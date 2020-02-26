<?php

namespace App\Observers;

use App\Events\Actor\ActorUpdated;
use App\Models\Actor;

class ActorObserver
{
    /**
     * Handle the actor "created" event.
     *
     * @param \App\Models\Actor $actor
     * @return void
     */
    public function created(Actor $actor)
    {
        //
    }

    /**
     * Handle the actor "updated" event.
     *
     * @param \App\Models\Actor $actor
     * @return void
     */
    public function updated(Actor $actor)
    {
        event(new ActorUpdated($actor));
    }

    /**
     * Handle the actor "deleted" event.
     *
     * @param \App\Models\Actor $actor
     * @return void
     */
    public function deleted(Actor $actor)
    {
        //
    }

    /**
     * Handle the actor "restored" event.
     *
     * @param \App\Models\Actor $actor
     * @return void
     */
    public function restored(Actor $actor)
    {
        //
    }

    /**
     * Handle the actor "force deleted" event.
     *
     * @param \App\Models\Actor $actor
     * @return void
     */
    public function forceDeleted(Actor $actor)
    {
        //
    }
}
