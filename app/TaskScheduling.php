<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskScheduling extends Model
{
    protected $table='task_scheduling';
    protected $primaryKey='id';
    protected $fillable=['tenant_id'];
}
