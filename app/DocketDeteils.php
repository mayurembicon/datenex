<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocketDeteils extends Model
{
    protected $table="docket_deteils";
    protected $primaryKey="docket_deteils_id";
    protected $fillable=['invoice_id','pi_id','delivery_location','courier_name','tracking_no'];
}
