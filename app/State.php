<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    protected $table='state';
    protected $primaryKey='state_id';
    protected $fillable=['state_name','country_id'];

}
