<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationPayment extends Model
{
    protected $table ='quotation_payment';
    protected $primaryKey ='payment_id';
    protected $fillable =['quotation_id','term_condition','notes','payment_terms_id'];

    public function getPayment(){
        return $this->belongsTo('App\PaymentTerms','payment_terms_id','payment_terms_id');
    }
}
