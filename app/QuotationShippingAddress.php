<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationShippingAddress extends Model
{

    protected $table = 'quotation_shipping_address';
    protected $primaryKey = 'shipping_address_id';
    protected $fillable = ['quotation_id', 'country_id', 'state_id',  'shipping_pincode', 'shipping_address'];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }
}
