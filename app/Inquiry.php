<?php

namespace App;

use App\Events\InquiryThankEvent;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $table = "inquiry";
    protected $primaryKey = "inquiry_id";
    protected $fillable = ['date', 'inquiry_from','subject', 'financial_year_id','assign_id', 'customer_name', 'contact_person', 'phone_no', 'email', 'notes', 'ratedIndex','inquiry_status'];


    public function quotation()
    {
        return $this->hasOne('App\Quotation', 'inquiry_id', 'inquiry_id');
    }
    public function inquiryproduct()
    {
        return $this->hasMany('App\InquiryProduct','inquiry_id','inquiry_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'customer_id');
    }

    public function gettaxrate()
    {
        return $this->belongsTo('App\TaxRate', 'taxrate', 'taxrate');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'assign_id', 'id');
    }
//
//    protected $dispatchesEvents = [
//        'created' => InquiryThankEvent::class,
//    ];
}
