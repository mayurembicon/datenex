<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assign extends Model
{ protected $table='assign';
    protected $primaryKey='assign_id';
    protected $fillable=['user_id','inquiry_id','date'];

}
