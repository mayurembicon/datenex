<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pi extends Model
{
    protected $table = "pi";
    protected $primaryKey = "pi_id";
    protected $fillable = [
        'financial_year_id',
        'customer_id','payment_terms_id','pi_no','taxrate','updated_id','quotation_id','created_id','email','ref_order_no','ref_order_date',
        'order_no', 'pi_date', 'due_date', 'pi_person' ,'notes',
        'pf','rounding_amount','grand_total','total_with_pf ','total','pf_taxrate','sales_status'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'customer_id');
    }
    public function piitem(){
        return $this->hasMany('App\PiItem','pi_id','pi_id');
    }

    public function paymentterms(){
        return $this->belongsTo('App\PaymentTerms','payment_terms_id','payment_terms_id');
    }
    public function taxrate(){
        return $this->belongsTo('App\TaxRate','taxrate','taxrate');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_id', 'id');
    }
}
