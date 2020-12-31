<?php

namespace App\Observers;

use App\Events\FollowupEvent;
use App\OnlineInquiry;
use Illuminate\Support\Facades\Auth;

class OnlineInquiryObserveer
{
    /**
     * Handle the online inquiry "created" event.
     *
     * @param  \App\OnlineInquiry  $onlineInquiry
     * @return void
     */
    public function created(OnlineInquiry $onlineInquiry)
    {
//        FollowupEvent::dispatch($onlineInquiry->o_id, 0, 0,0,0, 'Online Inquiry Created', Auth::user()->id,null);

    }

    /**
     * Handle the online inquiry "updated" event.
     *
     * @param  \App\OnlineInquiry  $onlineInquiry
     * @return void
     */
    public function updated(OnlineInquiry $onlineInquiry)
    {
        //
    }

    /**
     * Handle the online inquiry "deleted" event.
     *
     * @param  \App\OnlineInquiry  $onlineInquiry
     * @return void
     */
    public function deleted(OnlineInquiry $onlineInquiry)
    {
        //
    }

    /**
     * Handle the online inquiry "restored" event.
     *
     * @param  \App\OnlineInquiry  $onlineInquiry
     * @return void
     */
    public function restored(OnlineInquiry $onlineInquiry)
    {
        //
    }

    /**
     * Handle the online inquiry "force deleted" event.
     *
     * @param  \App\OnlineInquiry  $onlineInquiry
     * @return void
     */
    public function forceDeleted(OnlineInquiry $onlineInquiry)
    {
        //
    }
}
