<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{

    protected $table="purchase";
    protected $primaryKey="purchase_id";
    protected $fillable=['financial_year_id','customer_id','po_id','payment_terms_id','bill_no','order_no','bill_date','due_date',
    'total','pf','pf_taxrate','total_with_pf','rounding_amount','grand_total'
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
    public function purchaseproduct(){
        return $this->hasMany('App\PurchaseProduct','purchase_id','purchase_id');
    }

}
