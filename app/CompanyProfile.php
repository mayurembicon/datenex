<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{

    protected $table='company_profile';
    protected $primaryKey='id';
    protected $fillable=['company_name','gst_in','address1','address2','address3','c_logo','invoice_prefix','quotation_prefix','state_id','country_id','city','phone_no','pincode','bank_ac_no','bank_name','bank_ifsc_code'];

    public function getcountry()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }
    public function getstate()
    {
        return $this->belongsTo('App\State', 'state_id', 'state_id');
    }
}
