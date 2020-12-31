<?php

namespace App\Observers;

use App\Events\FollowupEvent;
use App\FollowUp;
use App\Quotation;
use Illuminate\Support\Facades\Auth;

class   QuotationObserver
{
    /**
     * Handle the quotation "created" event.
     *
     * @param  \App\Quotation  $quotation
     * @return void
     */


    public function created(Quotation $quotation)
    {
        FollowupEvent::dispatch( 0,$quotation->quotation_id, 0, 0, 0, 'Quotation Created', Auth::user()->id,null);
    }

    /**
     * Handle the quotation "updated" event.
     *
     * @param  \App\Quotation  $quotation
     * @return void
     */
    public function updated(Quotation $quotation)
    {
        FollowupEvent::dispatch( 0,$quotation->quotation_id, 0, 0, 0,'Quotation Updated', Auth::user()->id,null);

    }

    /**
     * Handle the quotation "deleted" event.
     *
     * @param  \App\Quotation  $quotation
     * @return void
     */
    public function deleted(Quotation $quotation)
    {
        FollowupEvent::dispatch( 0,$quotation->quotation_id, 0, 0, 0,'Quotation Deleted', Auth::user()->id,null);


    }

    /**
     * Handle the quotation "restored" event.
     *
     * @param  \App\Quotation  $quotation
     * @return void
     */
    public function restored(Quotation $quotation)
    {
        //
    }

    /**
     * Handle the quotation "force deleted" event.
     *
     * @param  \App\Quotation  $quotation
     * @return void
     */
    public function forceDeleted(Quotation $quotation)
    {
        //
    }
}
