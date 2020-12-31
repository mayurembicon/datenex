<?php

namespace App\Observers;

use App\Events\FollowupEvent;
use App\FollowUp;
use App\Inquiry;
use Illuminate\Support\Facades\Auth;

class InquiryObserver
{
    /**
     * Handle the inquiry "created" event.
     *
     * @param \App\Inquiry $inquiry
     * @return void
     */
    public function created(Inquiry $inquiry)
    {

//        event(new FollowupEvent(['inquiry_id'=>$inquiry_id,
//            'quotation_id'=>$quotation_id,
//        ]));
//        $timeline=new FollowUp();
//        $timeline->inquiry_id =$inquiry->inquiry_id;
//        $timeline->remark = 'Inquiry Created';
//        $timeline->next_followup_date = date('Y-m-d');
//        $timeline->created_id = Auth::user()->id;
//        $timeline->save();
        FollowupEvent::dispatch($inquiry->inquiry_id, 0, 0, 0, 0 ,'Inquiry Created', Auth::user()->id,null);
    }

    /**
     * Handle the inquiry "updated" event.
     *
     * @param \App\Inquiry $inquiry
     * @return void
     */
    public function updated(Inquiry $inquiry)
    {
        FollowupEvent::dispatch($inquiry->inquiry_id, 0, 0, 0, 0 , 'Inquiry Updated', Auth::user()->id,null);
    }

    /**
     * Handle the inquiry "deleted" event.
     *
     * @param \App\Inquiry $inquiry
     * @return void
     */
    public function deleted(Inquiry $inquiry)
    {
        FollowupEvent::dispatch($inquiry->inquiry_id, 0, 0, 0, 0 ,'Inquiry Deleted', Auth::user()->id,null);
    }

    /**
     * Handle the inquiry "restored" event.
     *
     * @param \App\Inquiry $inquiry
     * @return void
     */
    public function restored(Inquiry $inquiry)
    {
        //
    }

    /**
     * Handle the inquiry "force deleted" event.
     *
     * @param \App\Inquiry $inquiry
     * @return void
     */
    public function forceDeleted(Inquiry $inquiry)
    {
        //
    }
}
