<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table='country';
    protected $primaryKey="country_id";
    protected $fillable=['country_name'];

}
