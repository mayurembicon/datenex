<?php

namespace App;

use App\Events\PurchaseReturnAddEvent;
use App\Events\PurchaseReturnRemoveEvent;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    protected $table = "purchase_return_items";
    protected $primaryKey = "purchase_return_items_id";
    protected $fillable = ['purchase_return_id',
        'item_id',
        'qty',
        'rate',
        'p_description',
        'discount_rate',
        'discount_type',
        'discount_amount',
        'taxable_value',
        'taxrate',
        'gst_amount',
        'item_total_amount',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
    ];

    public function getItemName()
    {
        return $this->belongsTo('App\Item', 'item_id', 'item_id');

    }
//    protected $dispatchesEvents = [
//        'created' => PurchaseReturnRemoveEvent::class,
//        'updated'=> PurchaseReturnRemoveEvent::class,
//    ];
}
