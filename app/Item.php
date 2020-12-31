<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $table='itemmaster';
    protected $primaryKey='item_id';
    protected $fillable=['taxrate','name','unit','ratedIndex','hsn','sku','sale_rate','purchase_rate','discount_amount','descripation','type','opening_stock'];


    public function taxrate(){
        return $this->belongsTo('App\TaxRate', 'taxrate', 'taxrate');
    }


}
