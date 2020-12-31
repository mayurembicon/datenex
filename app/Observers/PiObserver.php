<?php

namespace App\Observers;

use App\Events\FollowupEvent;
use App\Pi;
use Illuminate\Support\Facades\Auth;

class PiObserver
{
    /**
     * Handle the pi "created" event.
     *
     * @param  \App\Pi  $pi
     * @return void
     */
    public function created(Pi $pi)
    {
        FollowupEvent::dispatch( 0,0, $pi->pi_id,0,0,'Pi Created', Auth::user()->id,null);

    }

    /**
     * Handle the pi "updated" event.
     *
     * @param  \App\Pi  $pi
     * @return void
     */
    public function updated(Pi $pi)
    {
        FollowupEvent::dispatch( 0,0, $pi->pi_id,0,0,'Pi Updated', Auth::user()->id,null);

    }

    /**
     * Handle the pi "deleted" event.
     *
     * @param  \App\Pi  $pi
     * @return void
     */
    public function deleted(Pi $pi)
    {
        FollowupEvent::dispatch( 0,0, $pi->pi_id,0,0,'Pi Deleted', Auth::user()->id,null);

    }

    /**
     * Handle the pi "restored" event.
     *
     * @param  \App\Pi  $pi
     * @return void
     */
    public function restored(Pi $pi)
    {
        //
    }

    /**
     * Handle the pi "force deleted" event.
     *
     * @param  \App\Pi  $pi
     * @return void
     */
    public function forceDeleted(Pi $pi)
    {
        //
    }
}
