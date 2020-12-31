<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table="purchase_order";
    protected $primaryKey="po_id";
    protected $fillable=[
        'financial_year_id',
        'customer_id','payment_terms_id','bill_no','order_no','bill_date','due_date','email','place_of_supply',
        'total','pf','pf_taxrate','total_with_pf','rounding_amount','grand_total','notes'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'customer_id');
    }
    public function taxrate()
    {
        return $this->belongsTo('App\TaxRate', 'taxrate', 'taxrate');
    }
    public function paymentterms()
    {
        return $this->belongsTo('App\PaymentTerms','payment_terms_id','payment_terms_id');
    }
    public function getpoproduct(){
        return $this->hasMany('App\PurchaseOrderProduct','po_id','po_id');
    }
    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function state()
    {
        return $this->belongsTo('App\State', 'place_of_supply', 'state_id');
    }

}
