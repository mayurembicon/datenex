<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOpeningBalance extends Model
{
    protected $table="customer_opening_balance";
    protected $primaryKey="customer_opening_balance_id";
    protected $fillable=['opening_balance','customer_id','financial_year_id','opening_balance_type'];

}
