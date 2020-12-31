<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReturnShippingAddress extends Model
{

    protected $table='sales_return_shipping_address';
    protected $primaryKey='sr_sa_id';
    protected $fillable=['sales_return_id','country_id','state_id','shipping_pincode','shipping_address'];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }
}
