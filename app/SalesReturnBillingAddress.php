<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReturnBillingAddress extends Model
{
    protected $table='sales_return_billing_adddress';
    protected $primaryKey='sr_ba_id';
    protected $fillable=['sales_return_id','country_id','state_id','zip_code','address','shipping_same_as_billing'];


    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }

}
