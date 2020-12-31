<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $table='setting';
    protected $primaryKey='id';
    protected $fillable=[

        'freight_tax_rate',
        'app_url',

        'trade_user_id',
        'trade_profile_id',
        'trade_key',
'indiamart_sync_time_limit',
        'india_mobile_no',
        'india_key',

        'last_indiamart_sync',
        'telegram_token_id',
        'mail_body',
        'telegram_api',

    ];
}
