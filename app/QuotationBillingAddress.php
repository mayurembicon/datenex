<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationBillingAddress extends Model
{
    protected $table='quotation_billing_address';
    protected $primaryKey='billing_address_id';
    protected $fillable=['quotation_id','country_id','state_id','zip_code','address','shipping_same_as_billing'];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }

}
