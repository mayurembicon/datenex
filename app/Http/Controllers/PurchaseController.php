<?php

namespace App\Http\Controllers;

use App\Category;
use App\CurrentStock;
use App\Customer;
use App\Events\OutstandingDeleteEvent;
use App\Events\OutstandingInsertEvent;
use App\Events\OutstandingUpdateEvent;
use App\Events\stockAddEvent;
use App\Events\stockRemoveEvent;
use App\FinancialYear;
use App\Item;
use App\Notifications\TelegramNotification;
use App\PaymentTerms;
use App\Purchase;
use App\PurchaseOrder;
use App\PurchaseOrderProduct;
use App\PurchaseProduct;
use App\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;


class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedCustomer='';
        $selectedBillNo='';
        $selectedOrderNo='';
        $selectedItem='';
        $search_item = $request->session()->get('search_purchase');
        $taxrate = TaxRate::all();
        $item = Item::all();
        $customers=Customer::all();
        $purchaseproduct = PurchaseProduct::all();
        $queryObject = DB::table('purchase')
            ->leftjoin('customer', 'customer.customer_id', '=', 'purchase.customer_id')
            ->select(['purchase.bill_no',
                'purchase.order_no',
                'purchase.purchase_id',
                'purchase.bill_date',
                'purchase.due_date',
                'customer.company_name']);


        if (!empty($search_item['company_name'])) {
            $queryObject->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
        if (!empty($search_item['bill_no'])) {
            $queryObject->whereRaw("`bill_no` LIKE '%" . $search_item['bill_no'] . "%'");
            $selectedBillNo = $search_item['bill_no'];

        }
        if (!empty($search_item['order_no'])) {
            $queryObject->whereRaw("`order_no` LIKE '%" . $search_item['order_no'] . "%'");
            $selectedOrderNo = $search_item['order_no'];

        }
        if (!empty($search_item['name'])) {
            $queryObject->join('purchase_product', 'purchase_product.purchase_id', '=', 'purchase.purchase_id');
            $queryObject->join('itemmaster', 'itemmaster.item_id', '=', 'purchase_product.item_id');
            $queryObject->whereRaw("`name` LIKE '%" . $search_item['name'] . "%'");
            $selectedItem = $search_item['name'];
        }
        $queryObject->get();
        $purchase = $queryObject->paginate(10);
        return view('purchase.index')->with(compact('customers','purchase', 'purchaseproduct', 'taxrate', 'item', 'search_item','selectedItem','selectedOrderNo','selectedBillNo','selectedCustomer'));
    }

    public function searchPurchase(Request $request)
    {
        $search = array();

        $search['bill_no'] = $request->post('bill_no');
        $search['order_no'] = $request->post('order_no');
        $search['company_name'] = $request->post('customer_id');
        $search['name'] = $request->post('item_id');
        $request->session()->put('search_purchase', $search);
        return redirect()->route('purchase.index');
    }

    public function clearPurchase(Request $request)
    {
        $request->session()->forget('search_purchase');
        return redirect()->route('purchase.index');
    }

    public function sendTelegram(Request $request, $quotation_id)
    {
        $userInfo = DB::table('users')->where('id', Auth::user()->id)->first();
        $telegramID = $userInfo->telegram_id;

        Notification::route('telegram', $telegramID)
            ->notify(new TelegramNotification($quotation_id,'Purchase'));
        $request->session()->flash('success', 'Message Sent Successfully..');
        return redirect()->route('purchase.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($po_id = null)

    {
        $purchase = PurchaseOrder::find($po_id);
        $taxrate = TaxRate::all();
        $payment = PaymentTerms::all();
        $customers = Customer::all();
        $item = Item::all();
        $purchaseproduct = [];

        if ($po_id) {
            $purchaseproductArray = PurchaseOrderProduct::with('getItemName')->where('po_id', $purchase->po_id)->get();
            $purchaseproduct = [];
            foreach ($purchaseproductArray as $Items) {
                array_push($purchaseproduct, [
                    'po_id' => $Items->po_id,
                    'po_product_id' => $Items->po_product_id,
                    'item_id' => $Items->item_id,
                    'name' => $Items->getItemName->name,
                    'p_description' => $Items->p_description,
                    'qty' => $Items->qty,
                    'rate' => $Items->rate,
                    'taxrate' => $Items->taxrate,
                    'cgst_amount' => $Items->cgst_amount,
                    'sgst_amount' => $Items->sgst_amount,
                    'igst_amount' => $Items->igst_amount,
                    'taxable_value' => $Items->taxable_value,
                    'discount_rate' => $Items->discount_rate,
                    'item_total_amount' => $Items->item_total_amount,
                ]);
            }
        }
        return view('purchase.create')->with(compact('customers', 'payment', 'purchaseproduct', 'item', 'taxrate', 'purchase'))->with('TY', 'I');;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $messages = [
            'bill_no.required' => 'Please Enter Bill No',
            'customer_id.required' => 'Please Select Customer',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',


        ];
        $rules = [
            'bill_no' => 'required|numeric',
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $purchase = new Purchase();
        $purchase->customer_id = $request->post('customer_id');
        $purchase->financial_year_id = $financial->financial_year_id;
        $purchase->po_id = $request->post('po_id');
        $purchase->bill_no = $request->post('bill_no');
        $purchase->order_no = $request->post('order_no');
        $purchase->bill_date = date('Y-m-d ', strtotime($request->post('bill_date')));
        $purchase->due_date = date('Y-m-d ', strtotime($request->post('due_date')));
        $purchase->payment_terms_id = $request->post('payment_terms_id');
        $purchase->total = $request->post('total');
        $purchase->notes = $request->post('notes');
        $purchase->save();
        if ($purchase->po_id){
            PurchaseOrder::where('po_id', $purchase->po_id)->update(['po_status'=>'Purchase Created']);
        }


        $purchaseID = $purchase->purchase_id;

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

            } else if ($request->post('discount_type') == 'R') {
                $discountAmount = round((($discountRate)), 3);
                $taxableValue = round(floatval($total) - $discountAmount, 3);
                $gstAmount = round(floatval((($taxableValue * $taxRate) / 100)), 3);
                $igst = round(floatval(($taxableValue * $taxRate) / 100), 3);
                $cgst = round(floatval($igst / 2), 3);
                $sgst = round(floatval($igst / 2), 3);
                $TotalAmount = round(floatval($taxableValue + $gstAmount), 3);
            }

            /** Stop Tax Calculation */

            /** PurchaseProduct table save */
            $purchaseproduct = new PurchaseProduct();
            $purchaseproduct->purchase_id = $purchaseID;
            $purchaseproduct->item_id = $item['item_id'];
            $purchaseproduct->p_description = $item['p_description'];
            $purchaseproduct->qty = $qty;
            $purchaseproduct->rate = $rate;
            $purchaseproduct->discount_rate = $discountRate;
            $purchaseproduct->discount_type = $request->post('discount_type');
            $purchaseproduct->discount_amount = $discountAmount;
            $purchaseproduct->taxable_value = $taxableValue;
            $purchaseproduct->taxrate = $taxRate;
            $purchaseproduct->gst_amount = $gstAmount;
            $purchaseproduct->cgst_amount = $cgst;
            $purchaseproduct->igst_amount = $igst;
            $purchaseproduct->sgst_amount = $sgst;
            $purchaseproduct->item_total_amount = $TotalAmount;
            $purchaseproduct->save();
            stockAddEvent::dispatch($purchaseproduct->item_id, $purchaseproduct->qty);


        }
        $itemSumTotal = DB::table('purchase_product')->where('purchase_id', $purchaseID)->sum('item_total_amount');
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
        Purchase::where('purchase_id', $purchaseID)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);


        OutstandingInsertEvent::dispatch('C', $purchase->financial_year_id, $purchase->bill_date, $GrandTotal, $purchase->purchase_id, $purchase->customer_id, 'PU','',$purchase->notes);

        $request->session()->flash('success', 'purchase created successfully');
        return redirect()->route('purchase.index');

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($Id)
    {
        $purchase = Purchase::find($Id);
        $payment = PaymentTerms::all();
        $item = Item::all();
        $taxrate = TaxRate::all();

        $purchaseproductArray = PurchaseProduct::with('getItemName')->where('purchase_id', $purchase->purchase_id)->get();
        $purchaseproduct = [];

        foreach ($purchaseproductArray as $Items) {
            array_push($purchaseproduct, [
                'purchase_id' => $Items->purchase_id,
                'purchase_product_id' => $Items->purchase_product_id,
                'item_id' => $Items->item_id,
                'name' => $Items->getItemName->name,
                'p_description' => $Items->p_description,
                'qty' => $Items->qty,
                'rate' => $Items->rate,
                'taxrate' => $Items->taxrate,
                'taxable_value' => $Items->taxable_value,
                'cgst_amount' => $Items->cgst_amount,
                'sgst_amount' => $Items->sgst_amount,
                'igst_amount' => $Items->igst_amount,
                'discount_rate' => $Items->discount_rate,
                'item_total_amount' => $Items->item_total_amount,

            ]);


        }
        return view('purchase.create')->with(compact('taxrate', 'purchase', 'payment', 'item', 'purchaseproduct'))->with('TY', 'U');


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        $messages = [
            'bill_no.required' => 'Please Enter Bill No',
            'customer_id.required' => 'Please Select Customer',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',
        ];
        $rules = [
            'bill_no' => 'required|numeric',
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $purchase->customer_id = $request->post('customer_id');
        $purchase->po_id = $request->post('po_id');
        $purchase->bill_no = $request->post('bill_no');
        $purchase->order_no = $request->post('order_no');
        $purchase->bill_date = date('Y-m-d ', strtotime($request->post('bill_date')));
        $purchase->due_date = date('Y-m-d ', strtotime($request->post('due_date')));
        $purchase->payment_terms_id = $request->post('payment_terms_id');
        $purchase->total = $request->post('total');
        $purchase->notes = $request->post('notes');
        $purchase->save();

        $purchaseID = $purchase->purchase_id;
        $getItemID = PurchaseProduct::where('purchase_id', $purchase->purchase_id)->get();

        foreach ($getItemID as $value) {
            stockRemoveEvent::dispatch($value->item_id, $value->qty);

        }


            PurchaseProduct::where('purchase_id', $purchase->purchase_id)->delete();
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

            } else if ($request->post('discount_type') == 'R') {
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
            $purchaseproduct = new PurchaseProduct();
            $purchaseproduct->purchase_id = $purchaseID;
            $purchaseproduct->item_id = $item['item_id'];
            $purchaseproduct->p_description = $item['p_description'];
            $purchaseproduct->qty = $qty;
            $purchaseproduct->rate = $rate;
            $purchaseproduct->discount_rate = $discountRate;
            $purchaseproduct->discount_type = $request->post('discount_type');
            $purchaseproduct->discount_amount = $discountAmount;
            $purchaseproduct->taxable_value = $taxableValue;
            $purchaseproduct->taxrate = $taxRate;
            $purchaseproduct->gst_amount = $gstAmount;
            $purchaseproduct->cgst_amount = $cgst;
            $purchaseproduct->igst_amount = $igst;
            $purchaseproduct->sgst_amount = $sgst;
            $purchaseproduct->item_total_amount = $TotalAmount;

            $purchaseproduct->save();
            stockAddEvent::dispatch($purchaseproduct->item_id, $purchaseproduct->qty);


        }

        $itemSumTotal = DB::table('purchase_product')->where('purchase_id', $purchaseID)->sum('item_total_amount');
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
        Purchase::where('purchase_id', $purchaseID)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);

        OutstandingUpdateEvent::dispatch($purchase->purchase_id, 'C', 'PU', $purchase->bill_date, $GrandTotal, $purchase->customer_id,'',$purchase->notes);

        $request->session()->flash('warning', 'purchase updated successfully');
        return redirect()->route('purchase.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID, Request $request)
    {

        $purchase = Purchase::find($ID);
        if ($purchase->po_id){
            PurchaseOrder::where('po_id', $purchase->po_id)->update(['po_status'=>'Pending']);
        }
        /** Start Removing old stock */
        $getItemID = PurchaseProduct::where('purchase_id', $purchase->purchase_id)->get();
        foreach ($getItemID as $value) {
            stockRemoveEvent::dispatch($value->item_id, $value->qty);

        }

        PurchaseProduct::where('purchase_id', $ID)->delete();

        $status = $message = '';
        if (Purchase::destroy($ID)) {
            $status = 'error';
            $message = 'purchase deleted successfully.';
            OutstandingDeleteEvent::dispatch($purchase->purchase_id, 'PU');
        } else {

            $status = 'info';
            $message = 'purchase failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('purchase.index');
    }

    public function getCustomerItems()
    {
        $CustomerID = \request()->input('customer_id');
        $customer = Customer::where('customer_id', $CustomerID)->first();

        return response()->json(['customer_notes' => $customer->customer_notes], 200);
    }

    public function getTaxrate()
    {
        $itemID = \request()->input('item_id');
        $taxrate = Item::where('item_id', $itemID)->get();
        $options = '<option value="">TaxRate</option>';
        foreach ($taxrate as $item) {
            $options .= '<option value="' . $item->taxrate . '">' . $item->taxrate . '</option>';

        }
        return response()->json(['taxrate' => $options], 200);


    }


//    public function removeStockItems($itemID, $qty)
//    {
//
////        print_r($sum);exit();
//
//        /** Start Removing Old Stock */
//        $currentStockData = CurrentStock::where('item_id', $itemID)->first();
//
//        $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
//        CurrentStock::where('item_id', $itemID)->update(['current_stock' => $currentStockQty - $qty]);
//
//
//        /** Stop Removing old stock */
//    }
//
//    public function addStockItems($itemID, $qty)
//    {
//        /** Start Updating Stock */
//
//        $currentStockData = CurrentStock::where('item_id', $itemID)->first();
//
//        $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
//        CurrentStock::where('item_id', $itemID)->update(['current_stock' => $currentStockQty + $qty]);
//    }
}
