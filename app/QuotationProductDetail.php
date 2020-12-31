<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationProductDetail extends Model
{
    protected $table = "quotation_product_detail";
    protected $primaryKey = "product_detail_id";
    protected $fillable = ['quotation_id', 'item_id',
        'p_description','qty','rate','discount_rate','discount_type','discount_amount','taxable_value','taxrate','gst_amount','item_total_amount','cgst_amount','sgst_amount','igst_amount'];

    public function getItemName()
    {
        return $this->belongsTo('\App\Item', 'item_id', 'item_id');
    }
}
