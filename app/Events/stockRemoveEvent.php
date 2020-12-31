<?php

namespace App\Events;

use App\CurrentStock;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class stockRemoveEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($item_id=0,$qty=null)
    {
        $currentStockData = CurrentStock::where('item_id', $item_id)->first();
        $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
        CurrentStock::where('item_id', $item_id)->update(['current_stock' =>$currentStockQty - $qty]);
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
