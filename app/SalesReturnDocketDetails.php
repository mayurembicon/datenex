<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReturnDocketDetails extends Model
{
    protected $table="sales_return_docket_details";
    protected $primaryKey="srdd_id";
    protected $fillable=[
        'sales_return_id',
        'delivery_location',
        'courier_name',
        'tracking_no'];
}
