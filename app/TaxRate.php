<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    protected $table="taxrate";
    protected $primaryKey="taxrate";
    protected $fillable=['tax_rate'];
}
