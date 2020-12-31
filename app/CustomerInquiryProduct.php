<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerInquiryProduct extends Model
{
    protected $table="customer_inquiry_product";
    protected $primaryKey="customer_inquiry_product_id";
    protected $fillable=['customer_inquiry_id','item_name','p_description','qty'];

    public function getcusinquiry()
    {
        return $this->belongsTo('App\CustomerInquiry','customer_inquiry_product_id','customer_inquiry_product_id');

    }
}
