<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PiBillingAddress extends Model
{
    protected $table='pi_billing_address';
    protected $primaryKey='pi_ba_id';
    protected $fillable=['pi_id','country_id','state_id','zip_code','address','shipping_same_as_billing'];
    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }

}
