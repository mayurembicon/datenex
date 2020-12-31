<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $table = "purchase_return";
    protected $primaryKey = "purchase_return_id";
    protected $fillable = [
        'docket_transport_deteils',
        'financial_year_id',
        'customer_id',
        'original_purchase_no',
        'original_purchase_date',
        'total',
        'pf',
        'pf_taxrate',
        'total_with_pf',
        'rounding_amount',
        'grand_total',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'customer_id');
    }
}
