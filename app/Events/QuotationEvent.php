<?php

namespace App\Events;

use App\FollowUp;
use App\Inquiry;
use App\Quotation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class QuotationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $quotation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Quotation  $quotation)
    {
        $timeline=new FollowUp();
        $timeline->inquiry_id =$quotation->inquiry_id;
        $timeline->remark = 'quotation Created';
        $timeline->next_followup_date = date('Y-m-d');
        $timeline->created_id = Auth::user()->id;
        $timeline->save();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
