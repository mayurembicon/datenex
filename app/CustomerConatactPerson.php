<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class

CustomerConatactPerson extends Model
{
    protected $table = "customer_contact_person";
    protected $primaryKey = "contact_person_id";
    protected $fillable = ['customer_id', 'salutation', 'contact_person_name', 'email', 'phone_no', 'designation', 'department'];

    public function getCustomer(){
        return $this->belongsTo('\App\Customer','customer_id','customer_id');
    }
}





