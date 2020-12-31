<?php

namespace App\Events;

use App\DocketDeteils;
use App\Journal;
use App\Sales;
use App\SalesItems;
use App\TransportDeteils;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class OutstandingUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        $transactionID = null,
        $type = null,
        $reftype = null,
        $date = null,
        $grandTotal = null,
        $customerID = null,
        $transactionType = null,
        $descripation = null
    )
    {

        /** start outstanding update */


        Journal::where('transaction_id', $transactionID)->where('ref_type', $reftype)
            ->update([
                'transaction_id' => $transactionID,
                'customer_id' => $customerID,
                'type' => $type,
                'ref_type' => $reftype,
                'date' => $date,
                'grand_total' => $grandTotal,
                'transaction_type' => $transactionType,
                'description' => $descripation
            ]);
        /** end outstanding update */

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public
    function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
