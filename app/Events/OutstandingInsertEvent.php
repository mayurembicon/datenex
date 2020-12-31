<?php

namespace App\Events;

use App\Journal;
use App\Sales;
use App\SalesItems;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class OutstandingInsertEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct
    (

        $type = null,
        $financialID = null,
        $date = null,
        $grandtotal = null,
        $transactionID = null,
        $customerID = null,
        $reftype = null,
        $transactionType=null,
        $descripation=null
    )

    {
        /** Create new Journal */
        $journal = new Journal();
        $journal->type = $type;
        $journal->financial_year_id = $financialID;
        $journal->date = $date;
        $journal->grand_total = $grandtotal;
        $journal->transaction_id =$transactionID;
        $journal->customer_id = $customerID;
        $journal->ref_type = $reftype;
        $journal->transaction_type = $transactionType;
        $journal->description = $descripation;
        $journal->save();
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
