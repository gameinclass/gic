<?php

namespace App\Observers\Medal;

use App\Models\Medal;

class MedalObserver
{
    /**
     * Handle the medal "creating" event.
     *
     * @param \App\Models\Medal $medal
     * @return void
     */
    public function creating(Medal $medal)
    {
        $path = request()
            ->file('image')
            ->store('medals', 'public');

        $medal->path = $path;
    }

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
     * Handle the medal "saving" event.
     *
     * @param \App\Models\Medal $medal
     * @return void
     */
    public function saving(Medal $medal)
    {
        $path = request()
            ->file('image')
            ->store('medals', 'public');

        $medal->path = $path;
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
