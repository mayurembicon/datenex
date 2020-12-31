<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpeningStock extends Model
{
   protected $table="opening_stock";
   protected $primaryKey="opening_stock_id";
   protected $fillable=['opening_stock','item_id','financial_year_id'];


}
