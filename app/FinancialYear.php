<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    protected $table='financial_year';
    protected $primaryKey='financial_year_id';
    protected $fillable=['start_date','end_date','current_year','is_default'];
}
