<?php

namespace App;

use App\Events\FollowupEvent;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $table='followup';
    protected $primaryKey='followup_id';
    protected $fillable=['inquiry_id','quotation_id','pi_id','o_id','c_i_id','remark','next_followup_date','created_id'];


    protected $dispatchesEvents = [
//        'created' => FollowupEvent::class,
    ];
}
