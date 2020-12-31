<?php

namespace App\Http\Controllers;

use App\CurrentStock;
use App\Customer;
use App\DocketDeteils;
use App\Events\OutstandingDeleteEvent;
use App\Events\OutstandingInsertEvent;
use App\Events\OutstandingUpdateEvent;
use App\Events\stockAddEvent;
use App\Events\stockRemoveEvent;
use App\Financial_Year;
use App\FinancialYear;
use App\Item;
use App\PaymentTerms;
use App\PiBillingAddress;
use App\PiShippingAddress;
use App\Sales;
use App\SalesReturn;
use App\SalesReturnBillingAddress;
use App\SalesReturnDocketDetails;
use App\SalesReturnItem;
use App\SalesReturnShippingAddress;
use App\SalesReturnTrasportDetails;
use App\TaxRate;
use App\TransportDeteils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_item = $request->session()->get('search_salesreturn');
        $selectedCustomer = '';
        $selectedOriginalInvoiceNo = '';
        $selectedItem = '';
        $customers = Customer::all();

        $salesReturnProduct = SalesReturnItem::all();
        $transportdeteils = TransportDeteils::all();
        $docketdeteils = DocketDeteils::all();
        $taxrate = TaxRate::all();
        $item = Item::all();

        $queryObject = DB::table('sales_return')
            ->leftjoin('customer', 'customer.customer_id', '=', 'sales_return.customer_id')
            ->leftjoin('invoice', 'invoice.invoice_id', '=', 'sales_return.invoice_id')
            ->select(['sales_return.sales_return_id','invoice.invoice_no',
                'sales_return.original_invoice_no',
                'customer.company_name']);

        if (!empty($search_item['company_name'])) {
            $queryObject->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
        if (!empty($search_item['original_invoice_no'])) {
            $queryObject->whereRaw("`original_invoice_no` LIKE '%" . $search_item['original_invoice_no'] . "%'");
            $selectedOriginalInvoiceNo = $search_item['original_invoice_no'];
        }
        if (!empty($search_item['name'])) {
            $queryObject->join('sales_return_items', 'sales_return_items.sales_return_id', '=', 'sales_return.sales_return_id');
            $queryObject->join('itemmaster', 'itemmaster.item_id', '=', 'sales_return_items.item_id');
            $queryObject->whereRaw("`name` LIKE '%" . $search_item['name'] . "%'");
            $selectedItem = $search_item['name'];

        }

        $queryObject->get();
        $salesReturn = $queryObject->paginate(10);
        return view('sales-return.index')->with(compact('salesReturnProduct', 'selectedItem', 'selectedCustomer', 'selectedOriginalInvoiceNo', 'salesReturn', 'item', 'transportdeteils', 'docketdeteils', 'taxrate', 'customers', 'search_item'));
    }

    public function searchSalesReturn(Request $request)
    {
        $search = array();
        $search['company_name'] = $request->post('customer_id');
        $search['original_invoice_no'] = $request->post('original_invoice_no');
        $search['name'] = $request->post('item_id');
        $request->session()->put('search_salesreturn', $search);
        return redirect()->route('sales-return.index');
    }

    public function clearSalesReturn(Request $request)
    {
        $request->session()->forget('search_salesreturn');
        return redirect()->route('sales-return.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sales = Sales::all();
        $item = Item::all();
        $taxrate = TaxRate::all();
        $payment = PaymentTerms::all();
        return view('sales-return.create')->with(compact('item', 'taxrate', 'payment', 'sales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $messages = [
            'original_invoice_no.required' => 'Please Enter  Original Invoice No',
            'customer_id.required' => 'Please Select Customer',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',
        ];
        $rules = [
            'original_invoice_no' => 'required',
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $salesReturn = new SalesReturn();

        $salesReturn->customer_id = $request->post('customer_id');
        $salesReturn->financial_year_id = $financial->financial_year_id;
        $salesReturn->invoice_id = is_numeric($request->post('original_invoice_no'))?$request->post('original_invoice_no'):0;
        $salesReturn->original_invoice_no = str_replace('NEW_','',(!is_numeric($request->post('original_invoice_no'))?$request->post('original_invoice_no'):0));
        $salesReturn->sales_return_date = date('Y-m-d ', strtotime($request->post('sales_return_date')));
        $salesReturn->original_invoice_date = date('Y-m-d ', strtotime($request->post('original_invoice_date')));
        $salesReturn->notes = $request->post('notes');
        $salesReturn->created_id = Auth::user()->id;
        $salesReturn->save();

        $salesReturnID = $salesReturn->sales_return_id;

        foreach ($request->post('grid_items') as $item) {
            $qty = empty($item['qty']) ? 0 : $item['qty'];
            $rate = empty($item['rate']) ? 0 : $item['rate'];
            $discountRate = empty($item['discount_rate']) ? 0 : $item['discount_rate'];
            $taxRate = empty($item['taxrate']) ? 0 : $item['taxrate'];
            $total = round(floatval($qty * $rate), 3);

            if ($request->post('discount_type') == 'P') {
                $discountAmount = round(((($total * $discountRate) / 100)), 3);
                $taxableValue = round(floatval($total) - $discountAmount, 3);
                $gstAmount = round(floatval((($taxableValue * $taxRate) / 100)), 3);
                $igst = round(floatval(($taxableValue * $taxRate) / 100), 3);
                $cgst = round(floatval($igst / 2), 3);
                $sgst = round(floatval($igst / 2), 3);
                $TotalAmount = round(floatval($taxableValue + $gstAmount), 3);

            } else {
                $discountAmount = round((($discountRate)), 3);
                $taxableValue = round(floatval($total) - $discountAmount, 3);
                $gstAmount = round(floatval((($taxableValue * $taxRate) / 100)), 3);
                $igst = round(floatval(($taxableValue * $taxRate) / 100), 3);
                $cgst = round(floatval($igst / 2), 3);
                $sgst = round(floatval($igst / 2), 3);
                $TotalAmount = round(floatval($taxableValue + $gstAmount), 3);
            }


            /** Stop Tax Calculation */

            /** salesitems table save */
            $salesitems = new SalesReturnItem();
            $salesitems->sales_return_id = $salesReturnID;
            $salesitems->item_id = $item['item_id'];
            $salesitems->p_description = $item['p_description'];
            $salesitems->qty = $qty;
            $salesitems->rate = $rate;
            $salesitems->discount_rate = $discountRate;
            $salesitems->discount_type = 'p';
            $salesitems->discount_amount = $discountAmount;
            $salesitems->taxable_value = $taxableValue;
            $salesitems->taxrate = $taxRate;
            $salesitems->gst_amount = $gstAmount;
            $salesitems->cgst_amount = $cgst;
            $salesitems->igst_amount = $igst;
            $salesitems->sgst_amount = $sgst;
            $salesitems->item_total_amount = $TotalAmount;
            $salesitems->save();
            stockAddEvent::dispatch($salesitems->item_id, $salesitems->qty);

        }
        $itemSumTotal = DB::table('sales_return_items')->where('sales_return_id', $salesReturnID)->sum('item_total_amount');
        $pf = $request->post('pf');
        $pftaxrate = $request->post('pf_taxrate');
        $pftaxablevalue = (($pf * $pftaxrate) / 100);
        $totalamount = $itemSumTotal;
        $totalpf = (($pf + $pftaxablevalue) + $totalamount);
        $RoundingAmount = $request->post('rounding_amount');
        if ($RoundingAmount > 0) {
            $totalpf + 1;

        } else {
            $totalpf - 1;
        }
        $GrandTotal = ($totalpf + $RoundingAmount);
        SalesReturn::where('sales_return_id', $salesReturnID)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);


        $salesDocketDet = new SalesReturnDocketDetails();
        $salesDocketDet->sales_return_id = $salesReturnID;
        $salesDocketDet->delivery_location = $request->post('delivery_location');
        $salesDocketDet->courier_name = $request->post('courier_name');
        $salesDocketDet->tracking_no = $request->post('tracking_no');
        $salesDocketDet->save();

        $salesTrasportDet = new SalesReturnTrasportDetails();
        $salesTrasportDet->sales_return_id = $salesReturnID;
        $salesTrasportDet->desp_through = $request->post('desp_through');
        $salesTrasportDet->transport_id = $request->post('transport_id');
        $salesTrasportDet->lorry_no = $request->post('lorry_no');
        $salesTrasportDet->lr_no = $request->post('lr_no');
        $salesTrasportDet->lr_date = date('Y-m-d ', strtotime($request->post('lr_date')));
        $salesTrasportDet->place_of_supply = $request->post('place_of_supply');
        $salesTrasportDet->save();


        $billing_address = new SalesReturnBillingAddress();
        $billing_address->sales_return_id = $salesReturnID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->city_name = $request->post('city_name');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();

        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address = new SalesReturnShippingAddress();
            $shipping_address->sales_return_id = $salesReturnID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $shipping_address->city_name = $request->post('city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address = new SalesReturnShippingAddress();
            $shipping_address->sales_return_id = $salesReturnID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->city_name = $request->post('shipping_city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }


        OutstandingInsertEvent::dispatch('C', $salesReturn->financial_year_id, $salesReturn->sales_return_date, $GrandTotal, $salesReturn->sales_return_id, $salesReturn->customer_id, 'SR');

        $request->session()->flash('success', 'Sales Return  created successfully');
        return redirect()->route('sales-return.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {
        $payment = PaymentTerms::all();
        $salesReturn = SalesReturn::find($ID);
        $docketdeteils = SalesReturnDocketDetails::where('sales_return_id', $salesReturn->sales_return_id)->first();
        $taxrate = TaxRate::all();
        $item = Item::all();
        $transportdeteils = SalesReturnTrasportDetails::where('sales_return_id', $salesReturn->sales_return_id)->first();

        $billing_address = SalesReturnBillingAddress::where('sales_return_id', $salesReturn->sales_return_id)->first();
        $shipping_address = SalesReturnShippingAddress::where('sales_return_id', $salesReturn->sales_return_id)->first();
        $salesReturnArray = SalesReturnItem::with('getItemName')->where('sales_return_id', $salesReturn->sales_return_id)->get();

        $salesitems = [];

        foreach ($salesReturnArray as $Items) {
            array_push($salesitems, [
                'sales_return_id' => $Items->sales_return_id,
                'sales_return_items_id' => $Items->sales_return_items_id,
                'item_id' => $Items->item_id,
                'name' => $Items->getItemName->name,
                'p_description' => $Items->p_description,
                'qty' => $Items->qty,
                'rate' => $Items->rate,
                'taxrate' => $Items->taxrate,
                'discount_type' => $Items->discount_type,
                'discount_rate' => $Items->discount_rate,
                'cgst_amount' => $Items->cgst_amount,
                'sgst_amount' => $Items->sgst_amount,
                'igst_amount' => $Items->igst_amount,
                'taxable_value' => $Items->taxable_value,
                'item_total_amount' => $Items->item_total_amount,

            ]);

        }
        return view('sales-return.create')->with(compact('payment', 'shipping_address', 'billing_address', 'taxrate', 'salesReturn', 'transportdeteils', 'docketdeteils', 'item', 'salesitems'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)
    {
        $messages = [
            'original_invoice_no.required' => 'Please Enter Original Invoice No',
            'customer_id.required' => 'Please Select Customer',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',
        ];
        $rules = [
            'original_invoice_no' => 'required',
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $financial = FinancialYear::where('is_default', 'Y')->first();

        $salesReturn = SalesReturn::find($ID);
        $salesReturn->customer_id = $request->post('customer_id');
        $salesReturn->financial_year_id = $financial->financial_year_id;
        $salesReturn->invoice_id = is_numeric($request->post('original_invoice_no'))?$request->post('original_invoice_no'):0;
        $salesReturn->original_invoice_no = str_replace('NEW_','',(!is_numeric($request->post('original_invoice_no'))?$request->post('original_invoice_no'):0));
        $salesReturn->sales_return_date = date('Y-m-d ', strtotime($request->post('sales_return_date')));
        $salesReturn->original_invoice_date = date('Y-m-d ', strtotime($request->post('original_invoice_date')));
        $salesReturn->notes = $request->post('notes');
        $salesReturn->created_id = Auth::user()->id;
        $salesReturn->save();

        $salesReturnID = $salesReturn->sales_return_id;

        SalesReturnDocketDetails::where('sales_return_id', $ID)->update(['delivery_location' => $request->post('delivery_location'), 'courier_name' => $request->post('courier_name'), 'tracking_no' => $request->post('tracking_no')]);
        SalesReturnTrasportDetails::where('sales_return_id', $ID)->update(['desp_through' => $request->post('desp_through'), 'transport_id' => $request->post('transport_id'), 'lorry_no' => $request->post('lorry_no'), 'lr_no' => $request->post('lr_no'), 'lr_date' => $request->post('lr_date'), 'place_of_supply' => $request->post('place_of_supply')]);

        $billing_address = SalesReturnBillingAddress::find($ID);
        $billing_address->sales_return_id = $salesReturnID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->city_name = $request->post('city_name');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();

        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address = SalesReturnShippingAddress::find($ID);
            $shipping_address->sales_return_id = $salesReturnID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $shipping_address->city_name = $request->post('city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address = SalesReturnShippingAddress::find($ID);
            $shipping_address->sales_return_id = $salesReturnID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->city_name = $request->post('shipping_city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }

        $getItemID = SalesReturnItem::where('sales_return_id', $salesReturn->sales_return_id)->get();

        foreach ($getItemID as $value) {
            stockRemoveEvent::dispatch($value->item_id, $value->qty);

        }
        SalesReturnItem::where('sales_return_id', $salesReturn->sales_return_id)->delete();
        foreach ($request->post('grid_items') as $item) {
            $qty = empty($item['qty']) ? 0 : $item['qty'];
            $rate = empty($item['rate']) ? 0 : $item['rate'];
            $discountRate = empty($item['discount_rate']) ? 0 : $item['discount_rate'];
            $taxRate = empty($item['taxrate']) ? 0 : $item['taxrate'];
            $total = round(floatval($qty * $rate), 3);

            if ($request->post('discount_type') == 'P') {
                $discountAmount = round(((($total * $discountRate) / 100)), 3);
                $taxableValue = round(floatval($total) - $discountAmount, 3);
                $gstAmount = round(floatval((($taxableValue * $taxRate) / 100)), 3);
                $igst = round(floatval(($taxableValue * $taxRate) / 100), 3);
                $cgst = round(floatval($igst / 2), 3);
                $sgst = round(floatval($igst / 2), 3);
                $TotalAmount = round(floatval($taxableValue + $gstAmount), 3);

            } else {
                $discountAmount = round((($discountRate)), 3);
                $taxableValue = round(floatval($total) - $discountAmount, 3);
                $gstAmount = round(floatval((($taxableValue * $taxRate) / 100)), 3);
                $igst = round(floatval(($taxableValue * $taxRate) / 100), 3);
                $cgst = round(floatval($igst / 2), 3);
                $sgst = round(floatval($igst / 2), 3);
                $TotalAmount = round(floatval($taxableValue + $gstAmount), 3);
            }


            /** Stop Tax Calculation */

            /** invoiceitems table save */
            /** salesitems table save */
            $salesitems = new SalesReturnItem();
            $salesitems->sales_return_id = $salesReturnID;
            $salesitems->item_id = $item['item_id'];
            $salesitems->p_description = $item['p_description'];
            $salesitems->qty = $qty;
            $salesitems->rate = $rate;
            $salesitems->discount_rate = $discountRate;
            $salesitems->discount_type = 'p';
            $salesitems->discount_amount = $discountAmount;
            $salesitems->taxable_value = $taxableValue;
            $salesitems->taxrate = $taxRate;
            $salesitems->gst_amount = $gstAmount;
            $salesitems->cgst_amount = $cgst;
            $salesitems->igst_amount = $igst;
            $salesitems->sgst_amount = $sgst;
            $salesitems->item_total_amount = $TotalAmount;
            $salesitems->save();

            stockAddEvent::dispatch($salesitems->item_id, $salesitems->qty);

        }
        $itemSumTotal = DB::table('sales_return_items')->where('sales_return_id', $salesReturnID)->sum('item_total_amount');
        $pf = $request->post('pf');
        $pftaxrate = $request->post('pf_taxrate');
        $pftaxablevalue = (($pf * $pftaxrate) / 100);
        $totalamount = $itemSumTotal;
        $totalpf = (($pf + $pftaxablevalue) + $totalamount);
        $RoundingAmount = $request->post('rounding_amount');
        if ($RoundingAmount > 0) {
            $totalpf + 1;

        } else {
            $totalpf - 1;
        }
        $GrandTotal = ($totalpf + $RoundingAmount);
        SalesReturn::where('sales_return_id', $salesReturnID)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);
        OutstandingUpdateEvent::dispatch($salesReturn->sales_return_id, 'C', 'SR', $salesReturn->sales_return_date, $GrandTotal, $salesReturn->customer_id, '', $salesReturn->notes);

        $request->session()->flash('success', 'Sales Return  Update successfully');
        return redirect()->route('sales-return.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID, Request $request)
    {
        $salesReturn = SalesReturn::find($ID);
        /** Start Removing old stock */

        SalesReturnBillingAddress::where('sales_return_id', $salesReturn->sales_return_id)->delete();
        SalesReturnShippingAddress::where('sales_return_id', $salesReturn->sales_return_id)->delete();
        $getItemID = SalesReturnItem::where('sales_return_id', $salesReturn->sales_return_id)->get();

        foreach ($getItemID as $value) {
            stockRemoveEvent::dispatch($value->item_id, $value->qty);
        }
        $status = $message = '';
        if (SalesReturn::destroy($salesReturn->sales_return_id)) {
            $status = 'error';
            $message = 'Sales Return deleted successfully.';
            OutstandingDeleteEvent::dispatch($salesReturn->sales_return_id, 'SR');
        } else {

            $status = 'info';
            $message = 'Sales Return failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('sales-return.index');
    }

    public function removeStockItems($itemID, $qty)
    {
        /** Start Removing Old Stock */
        $currentStockData = CurrentStock::where('item_id', $itemID)->first();

        $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
        CurrentStock::where('item_id', $itemID)->update(['current_stock' => $currentStockQty - $qty]);


        /** Stop Removing old stock */
    }

    public function addStockItems($itemID, $qty)
    {
        /** Start Updating Stock */
        $currentStockData = CurrentStock::where('item_id', $itemID)->first();
        $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
        CurrentStock::where('item_id', $itemID)->update(['current_stock' => $currentStockQty + $qty]);
    }
}
