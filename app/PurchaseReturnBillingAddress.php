<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnBillingAddress extends Model
{
    protected $table='purchase_return_billing_address';
    protected $primaryKey='pr_ba_id';
    protected $fillable=['purchase_return_id','country_id','state_id','zip_code','address','shipping_same_as_billing'];
    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }

}
