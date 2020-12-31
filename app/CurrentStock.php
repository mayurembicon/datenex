<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrentStock  extends Model
{
    protected $table="current_stock";
    protected $primaryKey="current_stock_id";
    protected $fillable=['current_stock','item_id'];


}
