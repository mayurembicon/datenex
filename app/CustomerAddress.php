<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $table = "customer_address";
    protected $primaryKey = "customer_address_id";
    protected $fillable = ['customer_id', 'billing_attention', 'country_id', 'billing_pincode','billing_address1', 'billing_address2', 'billing_address3', 'billing_city', 'billing_distinct', 'state_id','shipping_pincode', 'shipping_attention', 'country_id', 'shipping_address1', 'shipping_address2', 'shipping_address3', 'shipping_city', 'shipping_distinct', 'state_id'];

    public function getCustomer(){
        return $this->belongsTo('\App\Customer','customer_id','customer_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }

}

