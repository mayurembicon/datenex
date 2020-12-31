<?php

namespace App\Http\Controllers;

use App\CompanyProfile;
use App\CurrentStock;
use App\Customer;
use App\DocketDeteils;
use App\Events\OutstandingDeleteEvent;
use App\Events\OutstandingInsertEvent;
use App\Events\OutstandingUpdateEvent;
use App\Events\stockAddEvent;
use App\Events\stockRemoveEvent;
use App\FinancialYear;
use App\Notifications\TelegramNotification;
use App\Pi;
use App\PiBillingAddress;
use App\PiItem;
use App\PiShippingAddress;
use App\PurchaseOrder;
use App\PurchaseOrderProduct;
use App\Quotation;
use App\QuotationProductDetail;
use App\Sales;
use App\SalesBillingAddress;
use App\SalesReturnShippingAddress;
use App\SalesShippingAddress;
use App\Item;
use App\Library\FPDFExtensions;
use App\PaymentTerms;
use App\PurchaseProduct;
use App\QuotationBillingAddress;
use App\QuotationShippingAddress;
use App\SalesItems;
use App\Setting;
use App\TaxRate;
use App\TransportDeteils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Integer;

class SalesController extends Controller
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
        $selectedSaleseNo = '';

        $setting = Setting::first();
        $search_item = $request->session()->get('search_item');
        $transportdeteils = TransportDeteils::all();
        $docketdeteils = DocketDeteils::all();
        $taxrate = TaxRate::all();
        $item = Item::all();
        $customers = Customer::all();

        $salesitems = SalesItems::all();

        $queryObject = DB::table('invoice')
            ->leftjoin('customer', 'customer.customer_id', '=', 'invoice.customer_id')
            ->select(['invoice.invoice_no', 'invoice.invoice_id', 'invoice.order_no', 'invoice.invoice_date', 'invoice.due_date', 'customer.company_name'])
            ->select(['invoice.invoice_id',
                'invoice.invoice_no',
                'invoice.ref_order_no',
                'invoice.invoice_date',
                'invoice.due_date',
                'customer.company_name']);
        if (!empty($search_item['invoice_no'])) {
            $queryObject->whereRaw("`invoice_no` LIKE '%" . $search_item['invoice_no'] . "%'");
            $selectedSaleseNo = $search_item['invoice_no'];
        }

        if (!empty($search_item['company_name'])) {
            $queryObject->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];

        }
        if (!empty($search_item['name'])) {
            $queryObject->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoice_items.invoice_id');
            $queryObject->join('itemmaster', 'itemmaster.item_id', '=', 'invoice_items.item_id');
            $queryObject->whereRaw("`name` LIKE '%" . $search_item['name'] . "%'");
            $selectedItem = $search_item['name'];
        }
        $queryObject->get();
        $sales = $queryObject->paginate(10);
        return view('sales.index')->with(compact('setting', 'customers', 'taxrate', 'transportdeteils', 'docketdeteils', 'salesitems', 'sales', 'item', 'search_item','selectedItem','selectedCustomer','selectedSaleseNo'));

    }

    public function sendTelegram(Request $request, $quotation_id)
    {
        $userInfo = DB::table('users')->where('id', Auth::user()->id)->first();
        $telegramID = $userInfo->telegram_id;
        $file_name = $this->printInvoice($quotation_id,'Telegram');
        Notification::route('telegram', $telegramID)
            ->notify(new TelegramNotification($quotation_id,'Sales',$file_name));
        unlink('./telegram/'.$file_name);
        $request->session()->flash('success', 'Message Sent Successfully..');
        return redirect()->route('sales.index');
    }


    public function searchSales(Request $request)
    {
        $search = array();
        $search['invoice_no'] = $request->post('invoice_no');
        $search['name'] = $request->post('item_id');
        $search['company_name'] = $request->post('customer_id');

        $request->session()->put('search_item', $search);
        return redirect()->route('sales.index');
    }

    public function clearSales(Request $request)
    {
        $request->session()->forget('search_item');
        return redirect()->route('sales.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($transactionID = null, $transactionType = null)
    {
        $sales = Quotation::find($transactionID);
        $taxrate = TaxRate::all();
        $payment = PaymentTerms::all();
        $item = Item::all();

        $salesitems = [];
        if ($transactionType == 'Quotation') {
            $sales = Quotation::find($transactionID);

            $salesitemArray = QuotationProductDetail::with('getItemName')->where('quotation_id', $sales->quotation_id)->get();

            $salesitems = [];

            foreach ($salesitemArray as $Items) {
                array_push($salesitems, [
                    'invoice_id' => $Items->invoice_id,
                    'product_detail_id' => $Items->product_detail_id,
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
        if ($transactionType == 'PI') {
            $sales = Pi::find($transactionID);

            $salesitemArray = PiItem::with('getItemName')->where('pi_id', $transactionID)->get();

            $salesitems = [];

            foreach ($salesitemArray as $Items) {
                array_push($salesitems, [
                    'invoice_id' => $Items->invoice_id,
                    'product_detail_id' => $Items->product_detail_id,
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
        return view('sales.create')->with(compact('item', 'payment', 'taxrate', 'salesitems', 'sales'))->with('TY', 'I');

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
            'invoice_no.required' => 'Please Enter Invoice No',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'customer_id' => 'required',
            'invoice_no' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $sales = new Sales();
        $sales->customer_id = $request->post('customer_id');
        $sales->quotation_id = $request->post('quotation_id');
        $sales->pi_id = $request->post('pi_id');
        $sales->financial_year_id = $financial->financial_year_id;
        $sales->invoice_no = $request->post('invoice_no');
        $sales->ref_order_no = $request->post('ref_order_no');
        $sales->ref_order_date = date('Y-m-d ', strtotime($request->post('ref_order_date')));
        $sales->invoice_date = date('Y-m-d ', strtotime($request->post('invoice_date')));
        $sales->due_date = date('Y-m-d ', strtotime($request->post('due_date')));
        $sales->payment_terms_id = $request->post('payment_terms_id');
        $sales->sales_person = $request->post('sales_person');
        $sales->email = $request->post('email');
        $sales->notes = $request->post('notes');
        $sales->created_id = Auth::user()->id;
        $sales->save();
        if ($sales->quotation_id) {
            Quotation::where('quotation_id', $sales->quotation_id)->update(['quotation_status' => 'Sales Created']);
        } elseif ($sales->pi_id) {

            Pi::where('pi_id', $sales->pi_id)->update(['sales_status' => 'Sales Created']);
        }

        $salesID = $sales->invoice_id;
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
            $salesitems = new SalesItems();
            $salesitems->invoice_id = $salesID;
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


            stockRemoveEvent::dispatch($salesitems->item_id, $salesitems->qty);


        }
        $itemSumTotal = DB::table('invoice_items')->where('invoice_id', $salesID)->sum('item_total_amount');
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
        Sales::where('invoice_id', $sales->invoice_id)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);


        $billing_address = new SalesBillingAddress();
        $billing_address->invoice_id = $salesID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->city_name = $request->post('city_name');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();

        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address = new SalesShippingAddress();
            $shipping_address->invoice_id = $salesID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $shipping_address->city_name = $request->post('city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address = new SalesShippingAddress();
            $shipping_address->invoice_id = $salesID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->city_name = $request->post('shipping_city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }


        $docketdeteils = new DocketDeteils();
        $docketdeteils->invoice_id = $salesID;
        $docketdeteils->delivery_location = $request->post('delivery_location');
        $docketdeteils->courier_name = $request->post('courier_name');
        $docketdeteils->tracking_no = $request->post('tracking_no');
        $docketdeteils->save();

        $transportdeteils = new TransportDeteils();
        $transportdeteils->invoice_id = $salesID;;
        $transportdeteils->desp_through = $request->post('desp_through');
        $transportdeteils->transport_id = $request->post('transport_id');
        $transportdeteils->lorry_no = $request->post('lorry_no');
        $transportdeteils->lr_no = $request->post('lr_no');
        $transportdeteils->lr_date = date('Y-m-d ', strtotime($request->post('lr_date')));
        $transportdeteils->place_of_supply = $request->post('place_of_supply');
        $transportdeteils->save();


        OutstandingInsertEvent::dispatch('D', $sales->financial_year_id, $sales->invoice_date, $GrandTotal, $sales->invoice_id, $sales->customer_id, 'SL', '', $sales->notes);

        $request->session()->flash('success', 'sales created successfully');
        return redirect()->route('sales.index');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Sales $sales
     * @return \Illuminate\Http\Response
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Sales $sales
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {

        $sales = Sales::find($ID);
        $item = Item::all();
        $docketdeteils = DocketDeteils::where('invoice_id', $sales->invoice_id)->first();
        $billing_address = SalesBillingAddress::where('invoice_id', $sales->invoice_id)->first();

        $shipping_address = SalesShippingAddress::where('invoice_id', $sales->invoice_id)->first();
        $payment = PaymentTerms::all();
        $taxrate = TaxRate::all();

        $transportdeteils = TransportDeteils::where('invoice_id', $sales->invoice_id)->first();

        $salesitemsArray = SalesItems::with('getItemName')->where('invoice_id', $sales->invoice_id)->get();
        $salesitems = [];

        foreach ($salesitemsArray as $Items) {
            array_push($salesitems, [
                'invoice_id' => $Items->invoice_id,
                'invoice_items_id' => $Items->invoice_items_id,
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
        return view('sales.create')->with(compact('shipping_address', 'billing_address', 'taxrate', 'sales', 'transportdeteils', 'docketdeteils', 'item', 'salesitems', 'payment'))->with('TY', 'U');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Sales $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)
    {
        $messages = [
            'customer_id.required' => 'Please Select Customer',
            'invoice_no.required' => 'Please Enter Invoice No',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',
        ];
        $rules = [
            'customer_id' => 'required',
            'invoice_no' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();


        $financial = FinancialYear::where('is_default', 'Y')->first();
        $sales = Sales::find($ID);

        $sales->customer_id = $request->post('customer_id');

        $sales->financial_year_id = $financial->financial_year_id;
        $sales->invoice_no = $request->post('invoice_no');
        $sales->ref_order_no = $request->post('ref_order_no');
        $sales->ref_order_date = date('Y-m-d ', strtotime($request->post('ref_order_date')));
        $sales->invoice_date = date('Y-m-d ', strtotime($request->post('invoice_date')));
        $sales->due_date = date('Y-m-d ', strtotime($request->post('due_date')));
        $sales->payment_terms_id = $request->post('payment_terms_id');
        $sales->sales_person = $request->post('sales_person');
        $sales->notes = $request->post('notes');
        $sales->email = $request->post('email');
        $sales->save();


        $salesID = $sales->invoice_id;
        $getItemID = SalesItems::where('invoice_id', $sales->invoice_id)->get();

        foreach ($getItemID as $value) {
            stockAddEvent::dispatch($value->item_id, $value->qty);

        }

        SalesItems::where('invoice_id', $sales->invoice_id)->delete();
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
            $salesitems = new SalesItems();
            $salesitems->invoice_id = $salesID;
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


            stockRemoveEvent::dispatch($salesitems->item_id, $salesitems->qty);

        }

        $itemSumTotal = DB::table('invoice_items')->where('invoice_id', $salesID)->sum('item_total_amount');
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
        Sales::where('invoice_id', $sales->invoice_id)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);

        OutstandingUpdateEvent::dispatch($sales->invoice_id, 'D', 'SL', $sales->invoice_date, $GrandTotal, $sales->customer_id, '', $sales->notes);

        DocketDeteils::where('invoice_id', $sales->invoice_id)->update(['delivery_location' => $request->post('delivery_location'), 'courier_name' => $request->post('courier_name'), 'tracking_no' => $request->post('tracking_no')]);
        TransportDeteils::where('invoice_id', $sales->invoice_id)->update(['desp_through' => $request->post('desp_through'), 'transport_id' => $request->post('transport_id'), 'lorry_no' => $request->post('lorry_no'), 'lr_no' => $request->post('lr_no'), 'lr_date' => $request->post('lr_date'), 'place_of_supply' => $request->post('place_of_supply')]);


        $billing_address =  SalesBillingAddress::find($ID);
        $billing_address->invoice_id = $salesID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->city_name = $request->post('city_name');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();

        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address =  SalesShippingAddress::find($ID);
            $shipping_address->invoice_id = $salesID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $shipping_address->city_name = $request->post('city_name');
            $shipping_address->shipping_pincode = $request->post('zip_code');
            $shipping_address->shipping_address = $request->post('address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address =  SalesShippingAddress::find($ID);
            $shipping_address->invoice_id = $salesID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->city_name = $request->post('shipping_city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }

        $request->session()->flash('warning', 'sales updated successfully');
        return redirect()->route('sales.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Sales $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID, Request $request)
    {
        SalesShippingAddress::where('invoice_id', $ID)->delete();
        SalesBillingAddress::where('invoice_id', $ID)->delete();
        SalesItems::where('invoice_id', $ID)->delete();
        $sales = Sales::find($ID);

        if ($sales->quotation_id) {
            Quotation::where('quotation_id', $sales->quotation_id)->update(['quotation_status' => 'Pending']);
        } elseif ($sales->pi_id) {

            Pi::where('pi_id', $sales->pi_id)->update(['sales_status' => 'Pending']);
        }
        /** Start Removing old stock */

        $getItemID = SalesItems::where('invoice_id', $sales->invoice_id)->get();

        foreach ($getItemID as $value) {
            stockAddEvent::dispatch($value->item_id, $value->qty);

        }

        /** Stop Removing old stock */
        $status = $message = '';
        if (Sales::destroy($sales->invoice_id)) {
            $status = 'error';
            $message = 'sales deleted successfully.';
            OutstandingDeleteEvent::dispatch($sales->invoice_id, 'SL');

        } else {

            $status = 'info';
            $message = 'sales failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('sales.index');
    }

    public function removeStockItems($itemID, $qty)
    {

//        print_r($sum);exit();

        /** Start Removing Old Stock */
        $currentStockData = CurrentStock::where('item_id', $itemID)->first();

        $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
        CurrentStock::where('item_id', $itemID)->update(['current_stock' => $currentStockQty - $qty]);


        /** Stop Removing old stock */
    }

    public
    function addStockItems($itemID, $qty)
    {
        /** Start Updating Stock */

        $currentStockData = CurrentStock::where('item_id', $itemID)->first();

        $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
        CurrentStock::where('item_id', $itemID)->update(['current_stock' => $currentStockQty + $qty]);
    }

//
//    public function removeStockItems($itemID, $qty)
//    {
//        /** Start Removing Old Stock */
//        $currentStockData = CurrentStock::where('item_id', $itemID)->first();
//
//
//        $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
//
//        CurrentStock::where('item_id', $itemID)->update(['current_stock' => ($currentStockQty - $qty)]);
//
//        $comItems = InvoiceItems::where('invoice_items_id', $itemID)->get();
//
//
//        foreach ($comItems as $comItem) {
//            $currentStockData = CurrentStock::where('item_id',$comItem->item_id)->first();
//            $currentStockQty = isset($currentStockData->current_stock) ? $currentStockData->current_stock : 0;
//            $Qty = isset($comItem->qty) ? $comItem->qty : 0;
//            $updatedStockQty = $currentStockQty-($qty * $Qty);
//            CurrentStock::where('item_id', $itemID)->update(['current_stock' =>$updatedStockQty]);
//        }
//
//        /** Stop Removing old stock */
//    }

    public function printInvoice($id,$print_type = '')
    {
        $companyprofile=CompanyProfile::first();
        $salestransport =TransportDeteils::with('state')->where('invoice_id', $id)->first();
        $placeofsupply = isset($salestransport->state->state_name)?$salestransport->state->state_name:'';
        $company_state_name = isset($companyprofile->getstate->state_name)?$companyprofile->getstate->state_name:'';


        if($placeofsupply == $company_state_name){
            return $this->cgst($id,$print_type);
        }else{
            return $this->igst($id,$print_type);
        }



    }
    public function igst($id,$print_type = '')
    {

        $sales = Sales::with('customer')->where('invoice_id', $id)->first();
        $companyprofile = CompanyProfile::with('getstate')->with('getcountry')->first();
        $salesProduct = DB::table('invoice_items')->where('invoice_id', $id)->first();
        $salesdocket = DB::table('docket_deteils')->where('invoice_id', $id)->first();
        $salestransport = TransportDeteils::with('state')->where('invoice_id', $id)->first();

        $salesbillingAddress = SalesBillingAddress::with('country')->with('state')->where('invoice_id', $id)->first();
        $salesShippingAddress = SalesShippingAddress::with('country')->with('state')->where('invoice_id', $id)->first();
        $printType = '';

        $fpdf = new FPDFExtensions();

        $fpdf->AddPage('P', 'A4');
        $fpdf->AliasNbPages();
        $fpdf->SetMargins(4, 2, 2);
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFont('courier', '', 9);
        $fpdf->SetAutoPageBreak(true);
//        $fpdf->Image('./assets/logo/LogoWatera.jpg', 32, 60, 150, 0);
        $fpdf->SetWidths(array(101, 101));
        $fpdf->SetFont('courier', '', 9);
        $fpdf->Ln();
        $fpdf->CellFitScale(80, 5, '', 0, 0);
        $fpdf->CellFitScale(35, 5, 'TAX INVOICE', 0, 0, 'C');
        $fpdf->CellFitScale(75, 5, 'ORIGINAL/ DUPLICATE/ TRIPLICATE/ EXTRA', 0, 0, 'R');
        $fpdf->Ln();
        $fpdf->SetFont('courier', 'B', '8');
        $fpdf->CellFitScale(101, 4, 'Supplier (BILL FROM)', 'TRL', 0);
        $fpdf->SetFont('courier', '', '8');


        $fpdf->CellFitScale(47, 4, 'Invoice No:' . $sales->invoice_no, 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, 'Date : ' . date('d/m/Y', strtotime($sales->invoice_date)), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(101, 4, $companyprofile->company_name, 'L', 0);
        $fpdf->CellFitScale(47, 4, 'Desp.Through :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $salestransport->desp_through, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', '8');
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(95, 4, strtoupper($companyprofile->address1), '', 0);
        $fpdf->CellFitScale(47, 4, 'Transport GST/ID', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $salestransport->transport_id, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(95, 4, $companyprofile->address2, '', 0);
        $fpdf->CellFitScale(47, 4, 'LR No', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $salestransport->lr_no, 1, 0, 'L');
        $fpdf->Ln();

        if (date('d-m-Y', strtotime($sales->invoice_date) != "01-01-1970" && !empty($salestransport->lr_date))) {
            $lr_date = date('d/m/Y', strtotime($salestransport->lr_date));
        } else {
            $lr_date = '';
        }
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(95, 5, strtoupper($companyprofile->address3), '', 0, 'L');
        $fpdf->CellFitScale(47, 4, 'LR Date', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $lr_date, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', '8');
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(95, 4, 'COUNTRY:' . strtoupper(!empty($companyprofile->getcountry->country_name) ? $companyprofile->getcountry->country_name : '') . ', STATE:' . strtoupper(!empty($companyprofile->getstate->state_name) ? $companyprofile->getstate->state_name : '') . ', CITY:' . strtoupper(!empty($companyprofile->city) ? $companyprofile->city : '') . ' ,' . strtoupper(!empty($companyprofile->pincode) ? $companyprofile->pincode : ''), '', 0, 'L');

        $fpdf->CellFitScale(47, 4, 'Lorry No :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $salestransport->lorry_no, 1, 0, 'L');
        $fpdf->Ln();

        $fpdf->CellFitScale(101, 4, 'PH NO. :' . $companyprofile->phone_no, 'RL', 0);
        $fpdf->CellFitScale(47, 4, 'Place of Supply :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, (!empty($salestransport->state->state_name) ? $salestransport->state->state_name : ''), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(101, 4, 'PAN NO.:' . 'AABRT6162E ', 'RL', 0);
        $fpdf->CellFitScale(47, 4, 'Ref Order No   :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $sales->ref_order_no, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(101, 4, 'GST IN.:' . $companyprofile->gst_in, 'RLB', 0);
        $fpdf->CellFitScale(47, 4, 'Ref Order Date :' . '', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, date('d/m/Y', strtotime($sales->ref_order_date)), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->SetFont('courier', 'B', '8');
        $fpdf->CellFitScale(101, 4, 'Buyer (BILL TO)', 'TRL', 0);
        $fpdf->CellFitScale(101, 4, 'Recipient (SHIP TO)', 'TRL', 0, 'L');
        $fpdf->SetFont('courier', '', '8');
        $fpdf->Ln();
        $fpdf->SetWidths(array(101, 101));

        $part1 = $sales->customer->company_name . "\n" . $salesbillingAddress->address . "\n" . (!empty($salesbillingAddress->zip_code) ? ' - ' . $salesbillingAddress->zip_code : '') . ', State : ' . (!empty($salesbillingAddress->state->state_name) ? $salesbillingAddress->state->state_name : '') . ', City : ' . (!empty($salesbillingAddress->city_name) ? $salesbillingAddress->city_name : '') . ', Country : ' . (!empty($salesbillingAddress->country->country_name) ? $salesbillingAddress->country->country_name : '') . "\n" . 'PAN NO.: ' . '' . "\n" . 'GST IN  :' . $sales->customer->gst_no . "\n" . '  ' . '' . ' ' . '';


        $part2 = $sales->customer->company_name . "\n" . $salesShippingAddress->shipping_address . "\n" . (!empty($salesShippingAddress->shipping_pincode) ? ' - ' . $salesShippingAddress->shipping_pincode : '') . ', State : ' . (!empty($salesShippingAddress->state->state_name) ? $salesShippingAddress->state->state_name : '') . ', City : ' . (!empty($salesShippingAddress->city_name) ? $salesShippingAddress->city_name : '') . ', Country : ' . (!empty($salesShippingAddress->country->country_name) ? $salesShippingAddress->country->country_name : '') . "\n" . 'PAN NO.: ' . '' . "\n" . 'GST IN  :' . $sales->customer->gst_no . "\n" . '  ' . '' . ' ' . '';

        $fpdf->Row(array($part1, $part2), array('L', 'L'), '', '', true, 4);

        /** Create columns */

        $temp = 0;
        $fpdf->SetFont('courier', 'B', '8');

        $total_rows = 25;
//        $total_rows -=count($salesProduct);
        $fpdf->SetWidths(array(5, 7, 50, 15, 20, 26));
        $fpdf->SetFont('courier', 'B', '8');
        $fpdf->Cell(8, 5, 'NO', 'TL', 0, 'C');
        $fpdf->Cell(54, 5, 'Description of Goods / Service', 'TL', 0, 'C');
        $fpdf->Cell(10, 5, 'Qty', 'TL', 0, 'C');
        $fpdf->Cell(20, 5, 'Rate', 'TL', 0, 'C');
        $fpdf->Cell(20, 5, 'Total', 'TL', 0, 'C');
        $fpdf->Cell(20, 5, 'Discount', 'TL', 0, 'C');
        $fpdf->Cell(20, 5, 'Taxable', 'TL', 0, 'C');
        $fpdf->Cell(30, 5, 'IGST', 'TLR', 0, 'C');
        $fpdf->Cell(20, 5, 'Total', 'TLR', 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(8, 5, '', 'BL', 0, 'C');
        $fpdf->Cell(54, 5, '', 'BL', 0, 'C');
        $fpdf->Cell(10, 5, '', 'BL', 0, 'C');
        $fpdf->Cell(20, 5, 'INR', 'BL', 0, 'C');
        $fpdf->Cell(20, 5, 'INR', 'BL', 0, 'C');
        $fpdf->Cell(20, 5, 'INR', 'BL', 0, 'C');
        $fpdf->Cell(20, 5, 'value INR', 'BL', 0, 'C');
        $fpdf->Cell(10, 5, 'Rate', 'TBLR', 0, 'C');
        $fpdf->Cell(20, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->Cell(20, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', 8);
        $fpdf->SetFont('courier', '', 8);
        $i = 1;


        $itemTotal = $totalQty = $totalAmount = $totalDiscount = $totalTaxableValue = $totalIgst = $grandTotal = 0;
        $salesItem = SalesItems::with('getItemName')->where('invoice_id', $id)->get();
        $salesProductsDiscount = SalesItems::where('invoice_id', $id)->sum('discount_rate');


        $rowTotal = 25;
        foreach ($salesItem as $value) {
            $total = ($value->rate) * ($value->qty);
            $fpdf->CellFitScale(8, 5, $i++, '1', 0, 'C');
            $fpdf->CellFitScale(54, 5, $value->getItemName->name, '1', 0, 'L');
            $fpdf->CellFitScale(10, 5, $value->qty, 1, 0, 'C');
            $fpdf->CellFitScale(20, 5, $value->rate, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $total, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->discount_amount, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->taxable_value, 1, 0, 'R');
            $fpdf->CellFitScale(10, 5, $value->taxrate, '1', 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->igst_amount, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->item_total_amount, 1, 1, 'R');

            if (!empty($value->p_description)) {
                $fpdf->CellFitScale(202, 5, $value->p_description, 1, 1, 'L');
            }

            $itemTotal = $itemTotal + $value->item_total_amount;
            $totalQty += $value->qty;
            $totalAmount += $total;
            $totalDiscount += $value->discount_amount;
            $totalTaxableValue += $value->taxable_value;
            $totalIgst += $value->igst_amount;
            $grandTotal += $value->item_total_amount;
            $rowTotal -= 2;
        }
        for ($t = 1; $t <= $rowTotal; $t++) {
            $fpdf->CellFitScale(8, 5, '', 'LR', 0, 'C');
            $fpdf->CellFitScale(54, 5, '', 'LR', 0, 'L');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'C');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 1, 'R');

        }

        $grand_total = $grand_qty = 0;
        $totalQty = $totalQty;
        $totalAmount = $totalAmount;
        $totalDiscount = $totalDiscount;
        $totalTaxableValue = $totalTaxableValue;
        $totalIgst = $totalIgst;
        $grandTotal = $grandTotal;


        $fpdf->SetWidths(array(8, 54, 10, 20, 20, 20, 20, 10, 20, 20));
        $fpdf->Row(array('', 'Sub Total', $totalQty, '', number_format($totalAmount, 2), number_format($totalDiscount, 2), number_format($totalTaxableValue, 2), '', number_format($totalIgst, 2), number_format($grandTotal, 2)), array('C', 'L', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R'), false, '', true);
        $fpdf->SetWidths(array(62, 10, 40, 20, 40, 30));
        $fpdf->SetFont('courier', '', 8);

        $fpdf->SetWidths(array(62, 10, 40, 20, 40, 30));
//        $fpdf->Row(array('',   '(Cases)', '500', '',
//            '(Cases)',
//           '',
//            'Round off (+/-) ',
//            number_format(500, 2),
//            'Invoice Total INR',
//            number_format(round(500), 2)),
//            array('L', 'C', 'R', 'R', 'R', 'R'), false, '', true);
        $fpdf->SetWidths(array(202));

        $fpdf->SetFont('courier', '', 8);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->CellFitScale(25, 5, 'Bank A/c. No. : ', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_ac_no) ? $companyprofile->bank_ac_no : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Tax Rate ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($sales->pf_taxrate), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(25, 5, 'Bank Name :', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_name) ? $companyprofile->bank_name : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'P & F ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($sales->pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(25, 5, 'RTGS/IFSC Code :', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_ifsc_code) ? $companyprofile->bank_ifsc_code : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Total With PF ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($sales->total_with_pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(25, 5, ' ', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, ' ', 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Rounding ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($sales->rounding_amount), 2), 'R', 1, 'R');


        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(15, 5, '', 'LB', 0, 'L');
        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(105, 5, '', 'RB', 0, 'L');
        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(50, 5, 'Grand Total', 'LBT', 0, 'L', true);
        $fpdf->CellFitScale((32), 5, number_format(round($sales->grand_total), 2), 'RBT', 1, 'R', true);;
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->Row(array('TOTAL AMOUNT IN WORDS INR: ' . strtoupper($this->conver_num_text_money(round($sales->grand_total)))),
            array('L'), false, '', true);


        /* footer Start*/
        $fpdf->Cell(130, 4, 'E.& O.E', 'TL', 0);
        $fpdf->Cell(72, 4, 'For,EMBICON TECH HUB', 'TR', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(202, 4, 'Note.:', 'RL', 0);
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '1.GOODS ONCE SOLD WILL NOT BE ACCEPTED BACK UNDER ANY CIRCUMSTANCES.', 'L', 0);
        $fpdf->Cell(62, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '2.WE ARE NOT RESPONSIBLE FOR TRANSIT DAMAGE', 'L', 0);
        $fpdf->Cell(62, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '3.PAYMENT ARE TO BE MADE BY A/C PAYEE DD/CHEQUE/RTGS/NEFT ONLY', 'L', 0);
        $fpdf->Cell(62, 4, 'AUTHORISED SIGNATORY', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '4.THE COMPANY WILL NOT BE RESPONSIBLE FOR ANY CASH GIVEN TO ANYBODY.',
            'L', 0);
        $fpdf->Cell(62, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '5.WARRANTY APPLICABLE ONLY FOR MANUFACTURING DEFECT SUBJECT TO WATER LIFTING EQUIPMENT',
            'L',
            0);
        $fpdf->Cell(62, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '   RUN IN CLEAR COLD WATER. NO WARRANTY IN CASE OF BURN.', 'L', 0);
        $fpdf->Cell(62, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4,
            '6.POST SALE DISCOUNT/INEREST GIVEN/COLLECT BY WAY OF C.N./D.N. AS PER TERMS AGREED MUTUALLY',
            'L', 0);
        $fpdf->Cell(62, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '7.SUBJECT TO RAJKOT JURISDICTION ', 'LB', 0);
        $fpdf->Cell(62, 4, '', 'RB', 0, 'R');
        $fpdf->Ln();
        $fpdf->SetFont('courier', 'B', 8);
        /* footer End*/

        if ($print_type == 'SendMail') {
            $file_name = $fpdf->Output('', 'S');
            return $file_name;
        } else if ($print_type == 'Telegram') {
            $filename = 'sales_' . date('ymdhis') . '.pdf';
            $fpdf->Output('telegram/' . $filename, 'F');
            return $filename;
        } else {
            $fpdf->Output();
        }
        exit();
    }

    public function conver_num_text_money($number)
    {
        $no = round($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '',
            '1' => 'one',
            '2' => 'two',
            '3' => 'three',
            '4' => 'four',
            '5' => 'five',
            '6' => 'six',
            '7' => 'seven',
            '8' => 'eight',
            '9' => 'nine',
            '10' => 'ten',
            '11' => 'eleven',
            '12' => 'twelve',
            '13' => 'thirteen',
            '14' => 'fourteen',
            '15' => 'fifteen',
            '16' => 'sixteen',
            '17' => 'seventeen',
            '18' => 'eighteen',
            '19' => 'nineteen',
            '20' => 'twenty',
            '30' => 'thirty',
            '40' => 'forty',
            '50' => 'fifty',
            '60' => 'sixty',
            '70' => 'seventy',
            '80' => 'eighty',
            '90' => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ? "." . $words[$point / 10] . " " . $words[$point = $point % 10] : '';
        return $result . "ONLY  ";
    }


    public function cgst($id,$print_type = '')
    {

        $sales = Sales::with('customer')->where('invoice_id', $id)->first();
        $companyprofile=CompanyProfile::first();

        $salesProduct = DB::table('invoice_items')->where('invoice_id', $id)->first();
        $salesdocket = DB::table('docket_deteils')->where('invoice_id', $id)->first();
        $salestransport = TransportDeteils::with('state')->where('invoice_id', $id)->first();

        $salesbillingAddress = SalesBillingAddress::with('country')->with('state')->where('invoice_id', $id)->first();
        $salesShippingAddress = SalesShippingAddress::with('country')->with('state')->where('invoice_id', $id)->first();
        $printType = '';

        $fpdf = new FPDFExtensions();

        $fpdf->AddPage('P', 'A4');
        $fpdf->AliasNbPages();
        $fpdf->SetMargins(1.6, 2, 2);
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFont('courier', '', 9);
        $fpdf->SetAutoPageBreak(true);
//        $fpdf->Image('./assets/logo/LogoWatera.jpg', 32, 60, 150, 0);
        $fpdf->SetWidths(array(101, 101));
        $fpdf->SetFont('courier', '', 9);
        $fpdf->Ln();
        $fpdf->CellFitScale(80, 5, '', 0, 0);
        $fpdf->CellFitScale(35, 5, 'TAX INVOICE', 0, 0, 'C');
        $fpdf->CellFitScale(75, 5, 'ORIGINAL/ DUPLICATE/ TRIPLICATE/ EXTRA', 0, 0, 'R');
        $fpdf->Ln();
        $fpdf->SetFont('courier', 'B', '8');
        $fpdf->CellFitScale(103.5, 4, 'Supplier (BILL FROM)', 'TRL', 0);
        $fpdf->SetFont('courier', '', '8');
        $fpdf->CellFitScale(47.5, 4, 'Invoice No:' . $sales->invoice_no, 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, 'Date : ' . date('d/m/Y', strtotime($sales->invoice_date)), 1, 0, 'L');
        $fpdf->Ln();

        $fpdf->CellFitScale(103.5, 4, $companyprofile->company_name, 'RL', 0);
        $fpdf->CellFitScale(47.5, 4, 'Desp.Through :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $salestransport->desp_through, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', '8');
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(97.4, 4,  strtoupper($companyprofile->address1), '', 0);
        $fpdf->CellFitScale(47.5, 4, 'Transport GST/ID :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $salestransport->transport_id, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(97.4, 4, $companyprofile->address2,'', 0);
        $fpdf->CellFitScale(47.5, 4, 'LR No :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $salestransport->lr_no, 1, 0, 'L');
        $fpdf->Ln();


        if (date('d-m-Y', strtotime($sales->invoice_date) != "01-01-1970" && !empty($salestransport->lr_date))) {
            $lr_date = date('d/m/Y', strtotime($salestransport->lr_date));
        } else {
            $lr_date = '';
        }
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(97.4, 4, $companyprofile->address3  , '', 0);
        $fpdf->CellFitScale(47.5, 4, 'LR Date :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $lr_date, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(97.4, 4,  'COUNTRY:' . strtoupper(!empty($companyprofile->getcountry->country_name)?$companyprofile->getcountry->country_name:''). ', STATE:' .strtoupper(!empty($companyprofile->getstate->state_name)?$companyprofile->getstate->state_name:''). ', CITY:' .strtoupper(!empty($companyprofile->city)?$companyprofile->city:'').' ,'. strtoupper(!empty($companyprofile->pincode)?$companyprofile->pincode:''), '', 0,'L');

        $fpdf->CellFitScale(47.5, 4, 'Lorry No :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $salestransport->lorry_no, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(103.5, 4, 'PH NO. :' .  $companyprofile->phone_no, 'RL', 0);
        $fpdf->CellFitScale(47.5, 4, 'Place of Supply :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, (!empty($salestransport->state->state_name)?$salestransport->state->state_name:''), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(103.5, 4, 'PAN NO.: ' . 'AABCT6162E ', 'RL', 0);
        $fpdf->CellFitScale(47.5, 4, 'Ref Order No :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $sales->ref_order_no, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(103.5, 4, 'GST IN.:'.$companyprofile->gst_in, 'RLB', 0);
        $fpdf->CellFitScale(47.5, 4, 'Ref Order Date :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4,   date('d/m/Y', strtotime($sales->ref_order_date)), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->SetFont('courier', 'B', '8');
        $fpdf->CellFitScale(103.5, 4, 'Buyer (BILL TO)', 'TRL', 0);
        $fpdf->CellFitScale(103.5, 4, 'Recipient (SHIP TO)', 'TRL', 0, 'L');
        $fpdf->SetFont('courier', '', '8');
        $fpdf->Ln();
        $fpdf->SetWidths(array(103.5, 103.5));

        $part1 = $sales->customer->company_name . "\n" . $salesbillingAddress->address . "\n" . (!empty($salesbillingAddress->zip_code) ? ' - ' . $salesbillingAddress->zip_code : '') . ', State : ' .  (!empty($salesbillingAddress->state->state_name ) ? $salesbillingAddress->state->state_name : ''). ', City : ' .  (!empty($salesbillingAddress->city_name ) ? $salesbillingAddress->city_name : '') . ', Country : ' .(!empty($salesbillingAddress->country->country_name) ? $salesbillingAddress->country->country_name : '') . "\n" . 'PAN NO.: ' . '' . "\n" . 'GST IN  :' . $sales->customer->gst_no . "\n" . '  ' . '' . ' ' . '';


        $part2 = $sales->customer->company_name . "\n" . $salesShippingAddress->shipping_address . "\n" . (!empty($salesShippingAddress->shipping_pincode) ? ' - ' . $salesShippingAddress->shipping_pincode : '') . ', State : ' . (!empty($salesShippingAddress->state->state_name ) ? $salesShippingAddress->state->state_name : ''). ', City : ' .  (!empty($salesShippingAddress->city_name ) ? $salesShippingAddress->city_name : '') . ', Country : ' .  (!empty($salesShippingAddress->country->country_name) ? $salesShippingAddress->country->country_name : '') . "\n" . 'PAN NO.: ' . '' . "\n" . 'GST IN  :' . $sales->customer->gst_no . "\n" . '  ' . '' . ' ' . '';

        $fpdf->Row(array($part1, $part2), array('L', 'L'), '', '', true, 4);



        /** Create columns */

        $temp = 0;
        $fpdf->SetFont('courier', 'B', '8');

        $total_rows = 25;
//        $total_rows -=count($salesProduct);
        $fpdf->SetWidths(array(5, 7, 50, 15, 20, 26));
        $fpdf->SetFont('courier', 'B', '7');
        $fpdf->CellFitScale(8, 5, 'NO', 'TL', 0, 'C');
        $fpdf->CellFitScale(54, 5, 'Description of Goods / Service', 'TL', 0, 'C');
        $fpdf->CellFitScale(10, 5, 'Qty', 'TL', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Rate', 'TL', 0, 'C');
        $fpdf->CellFitScale(20, 5, 'Total', 'TL', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Discount', 'TL', 0, 'C');
        $fpdf->CellFitScale(20, 5, 'Taxable', 'TL', 0, 'C');
        $fpdf->CellFitScale(25, 5, 'SGST', 'TLR', 0, 'C');
        $fpdf->CellFitScale(25, 5, 'CGST', 'TLR', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Total', 'TLR', 0, 'C');
        $fpdf->Ln();
        $fpdf->CellFitScale(8, 5, '', 'BL', 0, 'C');
        $fpdf->CellFitScale(54, 5, '', 'BL', 0, 'C');
        $fpdf->CellFitScale(10, 5, '', 'BL', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'INR', 'BL', 0, 'C');
        $fpdf->CellFitScale(20, 5, 'INR', 'BL', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'INR', 'BL', 0, 'C');
        $fpdf->CellFitScale(20, 5, 'value INR', 'BL', 0, 'C');
        $fpdf->CellFitScale(10, 5, 'Rate', 'TBLR', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->CellFitScale(10, 5, 'Rate', 'TBLR', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', 8);
        $fpdf->SetFont('courier', '', 8);
        $i = 1;


        $itemTotal = $totalQty = $totalAmount = $totalDiscount = $totalTaxableValue = $totalSGST = $totalCGST = $grandTotal = 0;
        $salesItem = SalesItems::with('getItemName')->where('invoice_id', $id)->get();
        $salesProductsDiscount = SalesItems::where('invoice_id', $id)->sum('discount_rate');


        $rowTotal = 23;
        foreach ($salesItem as $value) {
            $total = ($value->rate) * ($value->qty);
            $fpdf->CellFitScale(8, 5, $i++, '1', 0, 'C');
            $fpdf->CellFitScale(54, 5, $value->getItemName->name, '1', 0, 'L');
            $fpdf->CellFitScale(10, 5, $value->qty, 1, 0, 'C');
            $fpdf->CellFitScale(15, 5, $value->rate, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $total, 1, 0, 'R');
            $fpdf->CellFitScale(15, 5, $value->discount_amount, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->taxable_value, 1, 0, 'R');
            $fpdf->CellFitScale(10, 5, $value->taxrate, '1', 0, 'R');
            $fpdf->CellFitScale(15, 5, $value->sgst_amount, 1, 0, 'R');
            $fpdf->CellFitScale(10, 5, $value->taxrate, 1, 0, 'R');
            $fpdf->CellFitScale(15, 5, $value->cgst_amount, 1, 0, 'R');
            $fpdf->CellFitScale(15, 5, $value->item_total_amount, 1, 1, 'R');

            if (!empty($value->p_description)) {
                $fpdf->CellFitScale(207, 5, $value->p_description, 1, 1, 'L');
            }

            $itemTotal = $itemTotal + $value->item_total_amount;
            $totalQty += $value->qty;
            $totalAmount += $total;
            $totalDiscount += $value->discount_amount;
            $totalTaxableValue += $value->taxable_value;
            $totalSGST += $value->sgst_amount;
            $totalCGST += $value->cgst_amount;
            $grandTotal += $value->item_total_amount;
            $rowTotal -= 2;
        }
        for ($t = 1; $t <= $rowTotal; $t++) {
            $fpdf->CellFitScale(8, 5, '', 'LR', 0, 'C');
            $fpdf->CellFitScale(54, 5, '', 'LR', 0, 'L');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'C');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(50, 5, '', 'LR', 1, 'R');


        }

        $grand_total = $grand_qty = 0;
        $totalQty = $totalQty;
        $totalAmount = $totalAmount;
        $totalDiscount = $totalDiscount;
        $totalTaxableValue = $totalTaxableValue;
        $totalCGST = $totalCGST;
        $totalSGST = $totalSGST;
        $grandTotal = $grandTotal;


        $fpdf->SetWidths(array(8, 54, 10, 15, 20, 15, 20, 10, 15, 10, 15, 15, 50));
        $fpdf->Row(array('', 'Sub Total', $totalQty, '', number_format($totalAmount, 2), number_format($totalDiscount, 2), number_format($totalTaxableValue, 2), '', number_format($totalSGST, 2), '', number_format($totalCGST, 2), number_format($grandTotal, 2)), array('C', 'L', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'), false, '', true);
        $fpdf->SetWidths(array(62, 10, 40, 20, 40, 30));
        $fpdf->SetFont('courier', '', 8);

        $fpdf->SetWidths(array(62, 10, 40, 20, 40, 30));
        $fpdf->SetWidths(array(202));

        $fpdf->SetFont('courier', '', 8);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->CellFitScale(28, 5, 'Bank A/c. No. :', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_ac_no)?$companyprofile->bank_ac_no:''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Tax Rate ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($sales->pf_taxrate), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, 'Bank Name :', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_name)?$companyprofile->bank_name:''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'P & F ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($sales->pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, 'RTGS/IFSC Code :', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_ifsc_code)?$companyprofile->bank_ifsc_code:''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Total With PF ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($sales->total_with_pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, ' ', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, ' ', 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Rounding ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($sales->rounding_amount), 2), 'R', 1, 'R');


        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(17, 5, '', 'LB', 0, 'L');
        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(105, 5, '', 'RB', 0, 'L');
        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(48, 5, 'Grand Total', 'LBT', 0, 'L', true);
        $fpdf->CellFitScale((37), 5, number_format(round($sales->grand_total), 2), 'RBT', 1, 'R', true);;
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array(207));
        $fpdf->Row(array('TOTAL AMOUNT IN WORDS INR: ' . strtoupper($this->conver_num_text_money(round($sales->grand_total)))),
            array('L'), false, '', true);


        /* footer Start*/
        $fpdf->Cell(130, 4, 'E.& O.E', 'TL', 0);
        $fpdf->Cell(77, 4, 'For,EMBICON TECH HUB', 'TR', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(207, 4, 'Note.:', 'RL', 0);
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '1.GOODS ONCE SOLD WILL NOT BE ACCEPTED BACK UNDER ANY CIRCUMSTANCES.', 'L', 0);
        $fpdf->Cell(67, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '2.WE ARE NOT RESPONSIBLE FOR TRANSIT DAMAGE', 'L', 0);
        $fpdf->Cell(67, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '3.PAYMENT ARE TO BE MADE BY A/C PAYEE DD/CHEQUE/RTGS/NEFT ONLY', 'L', 0);
        $fpdf->Cell(67, 4, 'AUTHORISED SIGNATORY', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '4.THE COMPANY WILL NOT BE RESPONSIBLE FOR ANY CASH GIVEN TO ANYBODY.',
            'L', 0);
        $fpdf->Cell(67, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '5.WARRANTY APPLICABLE ONLY FOR MANUFACTURING DEFECT SUBJECT TO WATER LIFTING EQUIPMENT',
            'L',
            0);
        $fpdf->Cell(67, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '   RUN IN CLEAR COLD WATER. NO WARRANTY IN CASE OF BURN.', 'L', 0);
        $fpdf->Cell(67, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4,
            '6.POST SALE DISCOUNT/INEREST GIVEN/COLLECT BY WAY OF C.N./D.N. AS PER TERMS AGREED MUTUALLY',
            'L', 0);
        $fpdf->Cell(67, 4, '', 'R', 0, 'R');
        $fpdf->Ln();
        $fpdf->Cell(140, 4, '7.SUBJECT TO RAJKOT JURISDICTION ', 'LB', 0);
        $fpdf->Cell(67, 4, '', 'RB', 0, 'R');
        $fpdf->Ln();
        $fpdf->SetFont('courier', 'B', 8);
        /* footer End*/

        if ($print_type == 'SendMail') {
            $file_name = $fpdf->Output('', 'S');
            return $file_name;
        } else if ($print_type == 'Telegram') {
            $filename = 'sales_' . date('ymdhis') . '.pdf';
            $fpdf->Output('telegram/' . $filename, 'F');
            return $filename;
        } else {
            $fpdf->Output();
        }
        exit();



    }

    public function getCustomer(Request $request)
    {


        $invoiceID = $request->input('invoice_id');
        $sales = DB::table('invoice')->where('invoice_id', $invoiceID)->first();

        return response()->json(['invoice_id' => $sales->invoice_id, 'email' => $sales->email], 200);
    }


    public function sendEmail(Request $request)
    {
        $mail_title = $request->input('mail_title');
        $mail_body = $request->input('mail_body');
        $email = $request->input('email');
        $mail_invoice_id = $request->input('mail_invoice_id');
        $type = 'Sales';

        $attachment = $request->input('attachment');
        if ($attachment == 'on') {
            $file_name = $this->printInvoice($mail_invoice_id, 'SendMail');
        } else {
            $file_name = '';
        }
        $details = [
            'title' => $mail_title,
            'body' => $mail_body,
            'attachment' => $file_name,
            'type'=>$type

        ];
        Mail::to($email)->send(new \App\Mail\MyTestMail($details));
        $request->session()->flash('success', 'Email Sent Successfully');
        return redirect()->route('sales.index');
    }


}
