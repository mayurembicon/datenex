<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PiShippingAddress extends Model
{
    protected $table = 'pi_shipping_address';
    protected $primaryKey = 'pi_sa_id';
    protected $fillable = ['pi_id', 'country_id', 'state_id', 'shipping_pincode', 'shipping_address'];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }
}
