<?php

namespace App;

use App\Events\InquiryThankEvent;
use App\Events\QuotationEvent;
use App\Events\QuotationFollowupEvent;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $table='quotation';
    protected $primaryKey='quotation_id';
    protected $fillable=['customer_id',
        'created_id','updated_id' ,
        'financial_year_id','inquiry_id',
        'q_date','reference','contact_person',
        'phone_no','email','gstin','total','pf','pf_taxrate','total_with_pf','rounding_amount','grand_total','dispatch_through','delivery_period',
        'quotation_status','state_id','quotation_no','display_quotation_no'
    ];


    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'customer_id');
    }

    public function quotationproduct()
    {
        return $this->hasMany('App\QuotationProductDetail','quotation_id','quotation_id');
    }


    public function inquiry()
    {
        return $this->belongsTo('App\Inquiry', 'inquiry_id', 'inquiry_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id', 'item_id');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_id', 'id');
    }
    public function getPayment()
    {
        return $this->belongsTo('App\QuotationPayment', 'quotation_id', 'quotation_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }

//    protected $dispatchesEvents = [
//        'created' =>QuotationEvent::class,
//            QuotationFollowupEvent::class,
//    ];

}


