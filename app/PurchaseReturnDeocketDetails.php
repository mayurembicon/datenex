<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDeocketDetails extends Model
{
    protected $table="purchase_return_docket_details";
    protected $primaryKey="prdd_id";
    protected $fillable=[
        'purchase_return_id',
        'delivery_location',
        'courier_name',
        'tracking_no'
    ];
}
