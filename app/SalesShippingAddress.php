<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesShippingAddress extends Model
{
    protected $table = 'invoice_shipping_address';
    protected $primaryKey = 'invoice_sa_id';
    protected $fillable = ['invoice_id','country_id','state_id','shipping_pincode', 'shipping_address'];


    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }
}

