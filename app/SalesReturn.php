<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    protected $table = "sales_return";
    protected $primaryKey = "sales_return_id";
    protected $fillable = [
        'financial_year_id',
        'customer_id',
        'original_invoice_no',
        'sales_return_date',
        'original_invoice_date',
        'total',
        'pf',
        'pf_taxrate',
        'total_with_pf',
        'rounding_amount',
        'grand_total',
        'notes',
        'invoice_id',
        'original_invoice_no',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'customer_id');
    }
    public function salesInvoice()
    {
        return $this->belongsTo('App\Sales', 'invoice_id', 'invoice_id');
    }




}
