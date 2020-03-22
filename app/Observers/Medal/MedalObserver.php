<?php

namespace App\Observers\Medal;

use App\Models\Medal;

class MedalObserver
{

    /**
     * Handle the medal "created" event.
     *
     * @param \App\Models\Medal $medal
     * @return void
     */
    public function created(Medal $medal)
    {
    }

    /**
     * Handle the medal "updated" event.
     *
     * @param \App\Models\Medal $medal
     * @return void
     */
    public function updated(Medal $medal)
    {
        //
    }

    /**
     * Handle the medal "deleted" event.
     *
     * @param \App\Models\Medal $medal
     * @return void
     */
    public function deleted(Medal $medal)
    {
        //
    }

    /**
     * Handle the medal "restored" event.
     *
     * @param \App\Models\Medal $medal
     * @return void
     */
    public function restored(Medal $medal)
    {
        //
    }

    /**
     * Handle the medal "force deleted" event.
     *
     * @param \App\Models\Medal $medal
     * @return void
     */
    public function forceDeleted(Medal $medal)
    {
        //
    }
}
