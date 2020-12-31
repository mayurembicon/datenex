<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = "invoice";
    protected $primaryKey = "invoice_id";
    protected $fillable = [
        'financial_year_id','customer_id','payment_terms_id','invoice_no','pf_taxrate','pi_id','email',
        'ref_order_no', 'ref_order_date','invoice_date', 'due_date','sales_person','notes',
        'pf', 'rounding_amount', 'grand_total','total_with_pf ','total'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'customer_id');
    }

    public function salesitems()
    {
        return $this->hasMany('App\SalesItems','invoice_id','invoice_id');
    }

    public function paymentterms()
    {
        return $this->belongsTo('App\PaymentTerms', 'payment_terms_id', 'payment_terms_id');
    }

    public function taxrate()
    {
        return $this->belongsTo('App\TaxRate', 'taxrate', 'taxrate');
    }
    public function getInvoiceAddress()
    {
        return $this->belongsTo('App\SalesBillingAddress', 'invoice_id', 'invoice_id');
    }


}
