<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReturnTrasportDetails extends Model
{
    protected $table="sales_return_transport_details";
    protected $primaryKey="srtd_id";
    protected $fillable=[
        'sales_return_id',
        'desp_through',
        'transport_id',
        'lorry_no',
        'lr_no',
        'lr_date',
        'place_of_supply'];
}
