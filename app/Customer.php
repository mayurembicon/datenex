<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = ['customer_type',
        'customer_name',
        'email',
        'company_name',
        'country_code',
        'f_phone_no',
        's_phone_no',
        'website',
        'contact_person_name',
        'gst_no',
        'remark',
        'place_of_supply',
        'payment_terms_id',
        'payment_terms',
        'notes'
    ];


    public function contactperson()
    {
        return $this->hasMany('App\CustomerConatactPerson');
    }

    public function paymentterms()
    {
        return $this->belongsTo('App\PaymentTerms', 'payment_terms_id', 'payment_terms_id');
    }

    public function getOpeningBalance()
    {
        return $this->belongsTo('App\CustomerOpeningBalance', 'customer_id', 'customer_id');
    }

}
