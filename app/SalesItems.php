<?php

namespace App;

use App\Events\InvoiceStockAddEvent;
use App\Events\InvoiceStockManage;
use App\Events\InvoiceStockRemoveEvent;
use App\Events\JournalEntryEvent;
use App\Events\JournalInvoiceEntry;
use App\Events\PurchaseStockRemoveEvent;
use Illuminate\Database\Eloquent\Model;

class SalesItems extends Model
{
    protected $table = "invoice_items";
    protected $primaryKey = "invoice_items_id";
    protected $fillable = ['invoice_id', 'item_id', 'qty', 'rate', 'p_description',
        'discount_rate', 'discount_type', 'discount_amount', 'taxable_value',
        'taxrate', 'gst_amount', 'item_total_amount', 'igst_amount',
        'sgst_amount', 'cgst_amount', 'terms_condition'];

    public function getItemName()
    {
        return $this->belongsTo('\App\Item', 'item_id', 'item_id');
    }

    public function gettaxrate()
    {
        return $this->belongsTo('App\TaxRate', 'taxrate', 'taxrate');
    }



}
