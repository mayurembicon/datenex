<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportDeteils extends Model
{
    protected $table="transport_deteils";
    protected $primaryKey="transport_deteils_id";
    protected $fillable=['invoice_id','pi_id','desp_through','transport_id','lorry_no','lr_no','lr_date','place_of_supply'];


    public function state()
    {
        return $this->belongsTo('App\State', 'place_of_supply', 'state_id');
    }
}
