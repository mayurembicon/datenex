<?php

namespace App;

use App\Events\SalesReturnAddEvent;
use App\Events\SalesReturnRemoveEvent;
use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    protected $table = "sales_return_items";
    protected $primaryKey = "sales_return_items_id";
    protected $fillable = ['sales_return_id',
        'item_id',
        'p_description',
        'qty',
        'rate',
        'discount_rate',
        'discount_type',
        'discount_amount',
        'taxable_value',
        'taxrate',
        'gst_amount',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        'item_total_amount'
    ];

    public function getItemName(){
        return $this->belongsTo('\App\Item','item_id','item_id');
    }
//    protected $dispatchesEvents = [
//        'created' => SalesReturnAddEvent::class,
//        'updated'=> SalesReturnRemoveEvent::class,
//    ];
}
