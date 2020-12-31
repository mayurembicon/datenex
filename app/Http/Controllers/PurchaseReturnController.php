<?php

namespace App\Http\Controllers;

use App\CurrentStock;
use App\Customer;
use App\CustomerOpeningBalance;
use App\DocketDeteils;
use App\Events\OutstandingDeleteEvent;
use App\Events\OutstandingInsertEvent;
use App\Events\OutstandingUpdateEvent;
use App\Events\stockAddEvent;
use App\Events\stockRemoveEvent;
use App\FinancialYear;
use App\Item;

use App\PaymentTerms;
use App\PiBillingAddress;
use App\PiShippingAddress;
use App\PurchaseReturn;

use App\PurchaseReturnBillingAddress;
use App\PurchaseReturnDeocketDetails;
use App\PurchaseReturnItem;
use App\PurchaseReturnShippingAddress;
use App\PurchaseReturnTrasportDetails;
use App\SalesReturnBillingAddress;
use App\SalesReturnDocketDetails;
use App\SalesReturnItem;
use App\SalesReturnShippingAddress;
use App\SalesReturnTrasportDetails;
use App\TaxRate;
use App\TransportDeteils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Utils;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedCustomer = '';
        $selectedItem = '';
        $selectedOriginalPurchaseNo = '';
        $customers = Customer::all();
        $transportdeteils = TransportDeteils::all();
        $docketdeteils = DocketDeteils::all();
        $taxrate = TaxRate::all();
        $item = Item::all();
        $search_item = $request->session()->get('search_purchasereturn');

        $queryObject = DB::table('purchase_return')
            ->join('customer', 'customer.customer_id', '=', 'purchase_return.customer_id')
            ->select(['purchase_return.purchase_return_id',
                'purchase_return.original_purchase_no',
                'customer.company_name']);
        if (!empty($search_item['company_name'])) {
            $queryObject->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
        if (!empty($search_item['original_purchase_no'])) {
            $queryObject->whereRaw("`original_purchase_no` LIKE '%" . $search_item['original_purchase_no'] . "%'");
            $selectedOriginalPurchaseNo = $search_item['original_purchase_no'];
        }
        if (!empty($search_item['name'])) {
            $queryObject->join('purchase_return_items', 'purchase_return_items.purchase_return_id', '=', 'purchase_return.purchase_return_id');
            $queryObject->join('itemmaster', 'itemmaster.item_id', '=', 'purchase_return_items.item_id');
            $queryObject->whereRaw("`name` LIKE '%" . $search_item['name'] . "%'");
            $selectedItem = $search_item['name'];
        }
        $queryObject->get();
        $purchaseReturn = $queryObject->paginate(10);
        return view('purchase-return.index')->with(compact('selectedOriginalPurchaseNo', 'selectedCustomer', 'selectedItem', 'taxrate', 'transportdeteils', 'docketdeteils', 'item', 'purchaseReturn', 'customers', 'search_item'));

    }

    public function searchPurchaseReturn(Request $request)
    {
        $search = array();
        $search['company_name'] = $request->post('customer_id');
        $search['original_purchase_no'] = $request->post('original_purchase_no');
        $search['name'] = $request->post('item_id');
        $request->session()->put('search_purchasereturn', $search);
        return redirect()->route('purchase-return.index');
    }

    public function clearPurchaseReturn(Request $request)
    {
        $request->session()->forget('search_purchasereturn');
        return redirect()->route('purchase-return.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = \App\Item::all();
        $customers = \App\Customer::all();
        $taxrate = TaxRate::all();
        $payment = PaymentTerms::all();
        return view('purchase-return.create')->with(compact('customers', 'item', 'taxrate', 'payment'));

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
            'customer_id.required' => 'Please Select Customer',
            'original_purchase_no.required' => 'Please Enter Original Purchase No',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'original_purchase_no' => 'required',
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $financial = FinancialYear::where('is_default', 'Y')->first();

        $purchaseReturn = new \App\PurchaseReturn();
        $purchaseReturn->financial_year_id = $financial->financial_year_id;
        $purchaseReturn->customer_id = $request->post('customer_id');
        $purchaseReturn->original_purchase_no = $request->post('original_purchase_no');
        $purchaseReturn->purchase_return_date = date('Y-m-d ', strtotime($request->post('purchase_return_date')));
        $purchaseReturn->original_purchase_date = date('Y-m-d ', strtotime($request->post('original_purchase_date')));
        $purchaseReturn->notes = $request->post('notes');
        $purchaseReturn->created_id = Auth::user()->id;
        $purchaseReturn->docket_transport_deteils = ($request->post('docket_transport_deteils') == 'on' ? 'Y' : 'N');
        $purchaseReturn->save();


        $purchaseReturnID = $purchaseReturn->purchase_return_id;

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
            $purchaseItem = new PurchaseReturnItem();
            $purchaseItem->purchase_return_id = $purchaseReturnID;
            $purchaseItem->item_id = $item['item_id'];
            $purchaseItem->p_description = $item['p_description'];
            $purchaseItem->qty = $qty;
            $purchaseItem->rate = $rate;
            $purchaseItem->discount_rate = $discountRate;
            $purchaseItem->discount_type = 'p';
            $purchaseItem->discount_amount = $discountAmount;
            $purchaseItem->taxable_value = $taxableValue;
            $purchaseItem->taxrate = $taxRate;
            $purchaseItem->gst_amount = $gstAmount;
            $purchaseItem->cgst_amount = $cgst;
            $purchaseItem->igst_amount = $igst;
            $purchaseItem->sgst_amount = $sgst;
            $purchaseItem->item_total_amount = $TotalAmount;
            $purchaseItem->save();

            stockRemoveEvent::dispatch($purchaseItem->item_id, $purchaseItem->qty);

        }

        $itemSumTotal = DB::table('purchase_return_items')->where('purchase_return_id', $purchaseReturnID)->sum('item_total_amount');
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
        PurchaseReturn::where('purchase_return_id', $purchaseReturnID)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);


        if ($request->post('docket_transport_deteils') != 'on') {

            $docketDetails = new \App\PurchaseReturnDeocketDetails();
            $docketDetails->purchase_return_id = $purchaseReturnID;
            $docketDetails->delivery_location = $request->post('delivery_location');
            $docketDetails->courier_name = $request->post('courier_name');
            $docketDetails->tracking_no = $request->post('tracking_no');
            $docketDetails->save();

            $transportDetails = new \App\PurchaseReturnTrasportDetails();
            $transportDetails->purchase_return_id = $purchaseReturnID;
            $transportDetails->desp_through = $request->post('desp_through');
            $transportDetails->transport_id = $request->post('transport_id');
            $transportDetails->lorry_no = $request->post('lorry_no');
            $transportDetails->lr_no = $request->post('lr_no');
            $transportDetails->lr_date = date('Y-m-d ', strtotime($request->post('lr_date')));
            $transportDetails->place_of_supply = $request->post('place_of_supply');
            $transportDetails->save();

        }

        $purchaseReturnID = $purchaseReturn->purchase_return_id;

        $billing_address = new PurchaseReturnBillingAddress();
        $billing_address->purchase_return_id = $purchaseReturnID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->city_name = $request->post('city_name');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();

        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address = new PurchaseReturnShippingAddress();
            $shipping_address->purchase_return_id = $purchaseReturnID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $shipping_address->city_name = $request->post('city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address = new PurchaseReturnShippingAddress();
            $shipping_address->purchase_return_id = $purchaseReturnID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->city_name = $request->post('shipping_city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }

        OutstandingInsertEvent::dispatch('D', $purchaseReturn->financial_year_id, $purchaseReturn->purchase_return_date, $GrandTotal, $purchaseReturn->purchase_return_id , $purchaseReturn->customer_id, 'PR');


        $request->session()->flash('success', 'Purchase Return  Update successfully');
        return redirect()->route('purchase-return.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\PurchaseReturn $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseReturn $purchaseReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\PurchaseReturn $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {
        $purchaseReturn = PurchaseReturn::find($ID);
        $item = Item::all();
        $taxrate = TaxRate::all();
        $payment = PaymentTerms::all();

        $transportdeteils = PurchaseReturnTrasportDetails::where('purchase_return_id', $purchaseReturn->purchase_return_id)->first();
        $docketdeteils = PurchaseReturnDeocketDetails::where('purchase_return_id', $purchaseReturn->purchase_return_id)->first();


        $shipping_address = PurchaseReturnShippingAddress::where('purchase_return_id', $purchaseReturn->purchase_return_id)->first();
        $billing_address = PurchaseReturnBillingAddress::where('purchase_return_id', $purchaseReturn->purchase_return_id)->first();


        $purchaseReturnArray = \App\PurchaseReturnItem::with('getItemName')->where('purchase_return_id', $purchaseReturn->purchase_return_id)->get();
        $purchaseitems = [];

        foreach ($purchaseReturnArray as $Items) {
            array_push($purchaseitems, [
                'purchase_return_id' => $Items->purchase_return_id,
                'purchase_return_items_id' => $Items->purchase_return_items_id,
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
        return view('purchase-return.create')->with(compact('billing_address', 'taxrate', 'docketdeteils', 'payment', 'transportdeteils', 'purchaseReturn', 'item', 'purchaseitems', 'shipping_address'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\PurchaseReturn $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)
    {
        $messages = [
            'customer_id.required' => 'Please Select Customer',
            'original_purchase_no.required' => 'Please Enter Original Purchase No',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'original_purchase_no' => 'required',
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $financial = FinancialYear::where('is_default', 'Y')->first();

        $purchaseReturn = \App\PurchaseReturn::find($ID);
        $purchaseReturn->customer_id = $request->post('customer_id');
        $purchaseReturn->original_purchase_no = $request->post('original_purchase_no');
        $purchaseReturn->purchase_return_date = date('Y-m-d ', strtotime($request->post('purchase_return_date')));
        $purchaseReturn->original_purchase_date = date('Y-m-d ', strtotime($request->post('original_purchase_date')));
        $purchaseReturn->notes = $request->post('notes');
        $purchaseReturn->created_id = Auth::user()->id;
        $purchaseReturn->docket_transport_deteils = ($request->post('docket_transport_deteils') == 'on' ? 'Y' : 'N');
        $purchaseReturn->save();
        $purchaseReturnID = $purchaseReturn->purchase_return_id;


        $billing_address = PurchaseReturnBillingAddress::find($ID);
        $billing_address->purchase_return_id = $purchaseReturnID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->city_name = $request->post('city_name');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();

        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address = PurchaseReturnShippingAddress::find($ID);
            $shipping_address->purchase_return_id = $purchaseReturnID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $shipping_address->city_name = $request->post('city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address = PurchaseReturnShippingAddress::find($ID);
            $shipping_address->purchase_return_id = $purchaseReturnID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->city_name = $request->post('shipping_city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }


        if ($request->input('docket_transport_deteils') != 'on') {
            \App\PurchaseReturnDeocketDetails::where('purchase_return_id', $ID)->update(['delivery_location' => $request->post('delivery_location'), 'courier_name' => $request->post('courier_name'), 'tracking_no' => $request->post('tracking_no')]);
            \App\PurchaseReturnTrasportDetails::where('purchase_return_id', $ID)->update(['desp_through' => $request->post('desp_through'), 'transport_id' => $request->post('transport_id'), 'lorry_no' => $request->post('lorry_no'), 'lr_no' => $request->post('lr_no'), 'lr_date' => $request->post('lr_date'), 'place_of_supply' => $request->post('place_of_supply')]);

        }
// else {
//            $docketDetails->delivery_location = null;
//            $docketDetails->courier_name = null;
//            $docketDetails->tracking_no = null;
//            $docketDetails->desp_through = null;
//            $transportDetails->transport_id = null;
//            $transportDetails->lorry_no = null;
//            $transportDetails->lr_no = null;
//            $transportDetails->lr_date = null;
//            $transportDetails->place_of_supply = null;
//        }
        $getItemID = PurchaseReturnItem::where('purchase_return_id', $purchaseReturn->purchase_return_id)->get();

        foreach ($getItemID as $value) {
            stockAddEvent::dispatch($value->item_id, $value->qty);

        }
        \App\PurchaseReturnItem::where('purchase_return_id', $purchaseReturn->purchase_return_id)->delete();
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
            $purchaseItem = new PurchaseReturnItem();
            $purchaseItem->purchase_return_id = $purchaseReturnID;
            $purchaseItem->item_id = $item['item_id'];
            $purchaseItem->p_description = $item['p_description'];
            $purchaseItem->qty = $qty;
            $purchaseItem->rate = $rate;
            $purchaseItem->discount_rate = $discountRate;
            $purchaseItem->discount_type = 'p';
            $purchaseItem->discount_amount = $discountAmount;
            $purchaseItem->taxable_value = $taxableValue;
            $purchaseItem->taxrate = $taxRate;
            $purchaseItem->gst_amount = $gstAmount;
            $purchaseItem->cgst_amount = $cgst;
            $purchaseItem->igst_amount = $igst;
            $purchaseItem->sgst_amount = $sgst;
            $purchaseItem->item_total_amount = $TotalAmount;
            $purchaseItem->save();
            stockRemoveEvent::dispatch($purchaseItem->item_id, $purchaseItem->qty);
        }
        $itemSumTotal = DB::table('purchase_return_items')->where('purchase_return_id', $purchaseReturnID)->sum('item_total_amount');
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
        PurchaseReturn::where('purchase_return_id', $purchaseReturnID)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);
        OutstandingUpdateEvent::dispatch($purchaseReturn->purchase_return_id , 'D', 'PR', $purchaseReturn->purchase_return_date, $GrandTotal, $purchaseReturn->customer_id, '', $purchaseReturn->notes);

        $request->session()->flash('success', 'Purchase Return  Update successfully');
        return redirect()->route('purchase-return.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\PurchaseReturn $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($ID, Request $request)
    {
        $purchaseReturn = PurchaseReturn::find($ID);
        /** Start Removing old stock */
        $getItemID = PurchaseReturnItem::where('purchase_return_id', $purchaseReturn->purchase_return_id)->get();
        PurchaseReturnBillingAddress::where('purchase_return_id', $purchaseReturn->purchase_return_id)->delete();
        PurchaseReturnShippingAddress::where('purchase_return_id', $purchaseReturn->purchase_return_id)->delete();
        foreach ($getItemID as $value) {
            stockAddEvent::dispatch($value->item_id, $value->qty);

        }
        $status = $message = '';
        if (PurchaseReturn::destroy($purchaseReturn->purchase_return_id)) {
            $status = 'error';
            $message = 'Purchase Return deleted successfully.';
            OutstandingDeleteEvent::dispatch($purchaseReturn->purchase_return_id, 'PR');
        } else {

            $status = 'info';
            $message = 'Purchase Return failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('purchase-return.index');
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
