<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class   Journal extends Model
{
    protected $table="journal";
    protected $primaryKey="journal_id";
    protected $fillable=[

        'type',
        'transaction_type',
        'date',
        'financial_year_id',
        'grand_total',
        'description',
        'transaction_id',
        'customer_id',
        'ref_type',
        'created_id',
        'updated_id'
    ];

    public function customer(){
        return $this->belongsTo('\App\Customer','customer_id','customer_id');
    }

    public function purchaseCustomer()
    {
        return $this
            ->belongsTo('\App\Purchase', 'purchase_id', 'journal_id')
            ->join('customer', 'purchase.customer_id', 'customer.customer_id');
    }
    public function salesCustomer()
    {
        return $this
                ->belongsTo('', 'purchase_id', 'journal_id')
            ->join('customer', 'purchase.customer_id', 'customer.customer_id');
    }
}
