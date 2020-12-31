<?php

namespace App\Events;

use App\Journal;
use App\Purchase;
use App\PurchaseProduct;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class JournalPurchaseEntry
{
    public $purchase;
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PurchaseProduct $purchaseProduct)
    {
        $purchaseID=$purchaseProduct->purchase_id;
        DB::table('journal')->where('transaction_id', $purchaseID)->where('ref_type', 'PU')->delete();
        $purchase = Purchase::find($purchaseID);
        $purchaseItemDeiatls = PurchaseProduct::select('item_total_amount')->where('purchase_id', $purchaseID)->get();
        $customerTotal = $purchaseItemDeiatls->sum('item_total_amount');
        $purchaseitems = new Journal();
        $purchaseitems->transaction_id = $purchaseID;
        $purchaseitems->type = 'C';
        $purchaseitems->ref_type = 'PU';
        $purchaseitems->customer_id = $purchase->customer_id;
        $purchaseitems->financial_year_id = $purchase->financial_year_id;
        $purchaseitems->customer_type = 'Customer';
        $purchaseitems->date= $purchase->bill_date;
        $purchaseitems->grand_total = $purchase->grand_total;
        $purchaseitems->total = $customerTotal;
        $purchaseitems->save();
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
