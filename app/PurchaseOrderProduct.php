<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderProduct extends Model
{
    protected $table="purchase_order_product";
    protected $primaryKey="po_product_id";
    protected $fillable = ['po_id', 'item_id',' qty','rate', 'p_description',
        'discount_rate', 'discount_type', 'discount_amount', 'taxable_value','taxrate',
        'gst_amount','item_total_amount','cgst_amount','sgst_amount','igst_amount','terms_condition'];

    public function getItemName(){
        return $this->belongsTo('\App\Item','item_id','item_id');
    }
}
