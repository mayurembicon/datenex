<?php

namespace App\Observers;

use App\CustomerInquiry;
use App\Events\FollowupEvent;
use Illuminate\Support\Facades\Auth;

class CustomerInquiryObserver
{
    /**
     * Handle the customer inquiry "created" event.
     *
     * @param  \App\CustomerInquiry  $customerInquiry
     * @return void
     */
    public function created(CustomerInquiry $customerInquiry)
    {
        FollowupEvent::dispatch(0,0,0,0, $customerInquiry->customer_inquiry_id,'Customer Inquiry Created',Auth::user()->id,null);
    }

    /**
     * Handle the customer inquiry "updated" event.
     *
     * @param  \App\CustomerInquiry  $customerInquiry
     * @return void
     */
    public function updated(CustomerInquiry $customerInquiry)
    {

    }

    /**
     * Handle the customer inquiry "deleted" event.
     *
     * @param  \App\CustomerInquiry  $customerInquiry
     * @return void
     */
    public function deleted(CustomerInquiry $customerInquiry)
    {

    }

    /**
     * Handle the customer inquiry "restored" event.
     *
     * @param  \App\CustomerInquiry  $customerInquiry
     * @return void
     */
    public function restored(CustomerInquiry $customerInquiry)
    {
    }

    /**
     * Handle the customer inquiry "force deleted" event.
     *
     * @param  \App\CustomerInquiry  $customerInquiry
     * @return void
     */
    public function forceDeleted(CustomerInquiry $customerInquiry)
    {
        //
    }
}
