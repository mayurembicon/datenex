<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class   InquiryProduct extends Model
{
    protected $table="inquiry_product";
    protected $primaryKey="inquiry_product_id";
    protected $fillable=['inquiry_id','item_id','unit',
        'p_description','qty','rate','discount_rate','discount_type','discount_amount','taxable_value','taxrate','gst_amount','item_total_amount','cgst_amount','sgst_amount','igst_amount'];

    public function getItemName(){
        return $this->belongsTo('\App\Item','item_id','item_id');
    }

}
