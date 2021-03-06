<?php

namespace App\Events;

use App\CurrentStock;
use App\SalesReturnItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SalesReturnRemoveEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $salesReturn;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SalesReturnItem $salesReturn)

    {
        $this->removeStockItems($salesReturn->item_id,$salesReturn->qty);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function removeStockItems($itemID, $qty)
    {
        /** Start Removing Old Stock */
        $currentStockData = CurrentStock::where('item_id', $itemID)->first();

        $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
        CurrentStock::where('item_id', $itemID)->update(['current_stock' =>$currentStockQty-$qty]);
        /** Stop Removing old stock */
    }
}
