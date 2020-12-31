<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table="unit";
    protected $primaryKey="unit";
    protected $fillable=['unit_name'];
}
