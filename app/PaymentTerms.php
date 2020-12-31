<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTerms extends Model
{
    protected $table="payment_terms";
    protected $primaryKey="payment_terms_id";
    protected $fillable=['payment_terms'];
}
