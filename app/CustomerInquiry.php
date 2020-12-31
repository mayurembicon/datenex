<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerInquiry extends Model
{
    protected $table = "customer_inquiry";
    protected $primaryKey = "customer_inquiry_id";
    protected $fillable = ['company_name','subject', 'contact_person', 'phone_no', 'email', 'notes', ];

    public function inquiryproduct()
    {
        return $this->hasMany('App\CustomerInquiryProduct');
    }
    public function getState()
    {
        return $this->belongsTo('App\State','state_id','state_id');
    }



}
