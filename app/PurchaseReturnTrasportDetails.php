<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnTrasportDetails extends Model
{
    protected $table="purchase_return_transport_details";
    protected $primaryKey="prtd_id";
    protected $fillable=[
        'purchase_return_id',
        'desp_through',
        'transport_id',
        'lorry_no',
        'lr_no',
        'lr_date',
        'place_of_supply'

    ];
}
