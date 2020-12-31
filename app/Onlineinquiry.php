<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnlineInquiry extends Model
{
    protected $table = 'online_inquiry';
    protected $primaryKey = 'o_id';
    protected $fillable = ['inquiry_from', 'inq_uuid', 'inq_date', 'sender_company', 'sender_name', 'sender_email', 'sender_other_email', 'sender_mobile', 'sender_other_mobile', 'sender_city', 'sender_state', 'sender_country', 'subject', 'notes', 'inq_full_info', 'inq_full_info'];

}
