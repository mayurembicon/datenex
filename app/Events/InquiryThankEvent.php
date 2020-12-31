<?php

namespace App\Events;

use App\FollowUp;
use App\Inquiry;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class InquiryThankEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inquiry;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Inquiry $inquiry)
    {
//        $this->inquiry = $inquiry;
        $details = [
            'title' => 'Mail from Datenics',
            'body' => 'This is for testing email '
        ];
        $timeline=new FollowUp();
        $timeline->inquiry_id =$inquiry->inquiry_id;
        $timeline->remark = 'Inquiry Created';
        $timeline->next_followup_date = date('Y-m-d');
        $timeline->created_id = Auth::user()->id;
        $timeline->save();
//        Mail::to($inquiry->email)->send(new \App\Mail\MyTestMail($details));


    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('channel-name');
    }
}
