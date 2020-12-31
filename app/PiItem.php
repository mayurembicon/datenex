<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class PiItem extends Model
{
    protected $table = "pi_items";
    protected $primaryKey = "pi_items_id";
    protected $fillable = ['pi_id', 'item_id','qty','rate','p_description',
        'discount_rate', 'discount_type', 'discount_amount', 'taxable_value',
        'taxrate','gst_amount','item_total_amount','igst_amount',
        'sgst_amount','cgst_amount','terms_condition'];

    public function getItemName(){
        return $this->belongsTo('\App\Item','item_id','item_id');
    }
    public  function gettaxrate(){
        return $this->belongsTo('App\TaxRate','taxrate','taxrate');
    }

}
