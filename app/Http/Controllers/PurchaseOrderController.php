<?php

namespace App\Http\Controllers;

use App\CompanyProfile;
use App\Customer;
use App\CustomerAddress;
use App\Financial_Year;
use App\FinancialYear;
use App\Item;
use App\Library\FPDFExtensions;
use App\Notifications\TelegramNotification;
use App\PaymentTerms;
use App\Pi;
use App\PiItem;
use App\PurchaseOrder;
use App\PurchaseOrderProduct;
use App\PurchaseProduct;
use App\Sales;
use App\SalesBillingAddress;
use App\SalesItems;
use App\SalesShippingAddress;
use App\Setting;
use App\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $setting = Setting::first();
        $selectedCustomer = '';
        $selectedBillNo = '';
        $selectedOrderNo = '';
        $selectedItem = '';
        $search_item = $request->session()->get('po_item');
        $taxrate = TaxRate::all();
        $item = Item::all();
        $poproduct = PurchaseProduct::all();
        $customers = Customer::all();

        $queryObject = DB::table('purchase_order')
            ->join('customer', 'customer.customer_id', '=', 'purchase_order.customer_id')
            ->select(['purchase_order.bill_no',
                'purchase_order.order_no',
                'purchase_order.po_status',
                'purchase_order.due_date',
                'purchase_order.po_id',
                'purchase_order.bill_date',
                'purchase_order.bill_date',
                'customer.company_name']);

        if (!empty($search_item['company_name'])) {
            $queryObject->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
//        if (!empty($search_item['bill_no'])) {
//            $queryObject->whereRaw("`bill_no` LIKE '%" . $search_item['bill_no'] . "%'");
//            $selectedBillNo = $search_item['bill_no'];
//
//        }
        if (!empty($search_item['order_no'])) {
            $queryObject->whereRaw("`order_no` LIKE '%" . $search_item['order_no'] . "%'");
            $selectedOrderNo = $search_item['order_no'];

        }
        if (!empty($search_item['name'])) {
            $queryObject->join('purchase_order_product', 'purchase_order_product.po_id', '=', 'purchase_order.po_id');
            $queryObject->join('itemmaster', 'itemmaster.item_id', '=', 'purchase_order_product.item_id');
            $queryObject->whereRaw("`name` LIKE '%" . $search_item['name'] . "%'");
            $selectedItem = $search_item['name'];
        }
        $queryObject->get();

        $po = $queryObject->paginate(10);
        return view('po.index')->with(compact('customers', 'po', 'poproduct', 'taxrate', 'item', 'search_item', 'selectedItem', 'selectedOrderNo', 'selectedBillNo', 'selectedCustomer', 'setting'));

    }

    public function searchPo(Request $request)
    {
        $search = array();
//        $search['bill_no'] = $request->post('bill_no');
        $search['order_no'] = $request->post('order_no');
        $search['company_name'] = $request->post('customer_id');
        $search['name'] = $request->post('item_id');
        $request->session()->put('po_item', $search);
        return redirect()->route('po.index');
    }

    public function clearPo(Request $request)
    {
        $request->session()->forget('po_item');
        return redirect()->route('po.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $taxrate = TaxRate::all();
        $payment = PaymentTerms::all();
        $customers = Customer::all();
        $item = Item::all();
        return view('po.create')->with(compact('customers', 'payment', 'item', 'taxrate'));

    }

    public function sendTelegram(Request $request, $quotation_id)
    {

        $userInfo = DB::table('users')->where('id', Auth::user()->id)->first();
        $telegramID = $userInfo->telegram_id;
        $file_name = $this->printPO($quotation_id,'PO');
        Notification::route('telegram', $telegramID,$file_name)
            ->notify(new TelegramNotification($quotation_id,'PO'));
        unlink('./telegram/'.$file_name);
        $request->session()->flash('success', 'Message Sent Successfully..');
        return redirect()->route('po.index');
    }


    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $financial = FinancialYear::where('is_default', 'Y')->first();

        $messages = [
            'customer_id.required' => 'Please Select Customer',
//            'bill_no.required' => 'Please Select Bill No',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',


        ];
        $rules = [
//            'bill_no' => 'required|numeric',
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $po = new PurchaseOrder();
        $po->customer_id = $request->post('customer_id');
        $po->financial_year_id = $financial->financial_year_id;
//        $po->bill_no = $request->post('bill_no');
        $po->order_no = $request->post('order_no');
        $po->email = $request->post('email');
        $po->place_of_supply = $request->post('place_of_supply');
        $po->bill_date = date('Y-m-d ', strtotime($request->post('bill_date')));
        $po->due_date = date('Y-m-d ', strtotime($request->post('due_date')));
        $po->payment_terms_id = $request->post('payment_terms_id');
        $po->total = $request->post('total');
        $po->notes = $request->post('notes');
        $po->save();


        $poID = $po->po_id;
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

            /** PurchaseProduct table save */
            $poproduct = new PurchaseOrderProduct();
            $poproduct->po_id = $poID;
            $poproduct->item_id = $item['item_id'];
            $poproduct->p_description = $item['p_description'];
            $poproduct->qty = $qty;
            $poproduct->rate = $rate;
            $poproduct->discount_rate = $discountRate;
            $poproduct->discount_type = $request->post('discount_type');
            $poproduct->discount_amount = $discountAmount;
            $poproduct->taxable_value = $taxableValue;
            $poproduct->taxrate = $taxRate;
            $poproduct->gst_amount = $gstAmount;
            $poproduct->cgst_amount = $cgst;
            $poproduct->igst_amount = $igst;
            $poproduct->sgst_amount = $sgst;
            $poproduct->item_total_amount = $TotalAmount;

            $poproduct->save();

        }
        $itemSumTotal = DB::table('purchase_order_product')->where('po_id', $poID)->sum('item_total_amount');
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
        PurchaseOrder::where('po_id', $poID)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);

        $request->session()->flash('success', 'purchase order created successfully');
        return redirect()->route('po.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($Id)
    {

        $po = PurchaseOrder::find($Id);


        $payment = PaymentTerms::all();
        $item = Item::all();
        $taxrate = TaxRate::all();

        $poproductArray = PurchaseOrderProduct::with('getItemName')->where('po_id', $po->po_id)->get();
        $poproduct = [];

        foreach ($poproductArray as $Items) {
            array_push($poproduct, [
                'po_id' => $Items->po_id,
                'po_product_id' => $Items->po_product_id,
                'p_description' => $Items->p_description,
                'item_id' => $Items->item_id,
                'name' => $Items->getItemName->name,
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
        return view('po.create')->with(compact('taxrate', 'po', 'payment', 'item', 'poproduct'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)
    {
        $messages = [
            'bill_no.required' => 'Please Select Bill No',
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
        $po = PurchaseOrder::find($ID);
        $po->customer_id = $request->post('customer_id');
//        $po->bill_no = $request->post('bill_no');
        $po->order_no = $request->post('order_no');
        $po->email = $request->post('email');
        $po->place_of_supply = $request->post('place_of_supply');
        $po->bill_date = date('Y-m-d ', strtotime($request->post('bill_date')));
        $po->due_date = date('Y-m-d ', strtotime($request->post('due_date')));
        $po->payment_terms_id = $request->post('payment_terms_id');
        $po->total = $request->post('total');
        $po->notes = $request->post('notes');


        /*Packing and forworfing Calculation */
        $pf = $request->post('pf');
        $pftaxrate = $request->post('pf_taxrate');
        $pftaxablevalue = (($pf * $pftaxrate) / 100);
        $totalamount = ($request->post('total'));
        $totalpf = (($pf + $pftaxablevalue) + $totalamount);
        $RoundingAmount = $request->post('rounding_amount');
        if ($RoundingAmount > 0) {
            $totalpf + 1;

        } else {
            $totalpf - 1;
        };
        $GrandTotal = ($totalpf + $RoundingAmount);
        $po->total = $totalamount;
        $po->pf = $pf;
        $po->pf_taxrate = $pftaxrate;
        $po->total_with_pf = $totalpf;
        $po->rounding_amount = $RoundingAmount;
        $po->grand_total = $GrandTotal;/*end  Calculation */;
        $po->save();


        $poID = $po->po_id;
        PurchaseOrderProduct::where('po_id', $po->po_id)->delete();

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
            $poproduct = new PurchaseOrderProduct();
            $poproduct->po_id = $poID;
            $poproduct->item_id = $item['item_id'];
            $poproduct->p_description = $item['p_description'];
            $poproduct->qty = $qty;
            $poproduct->rate = $rate;
            $poproduct->discount_rate = $discountRate;
            $poproduct->discount_type = $request->post('discount_type');
            $poproduct->discount_amount = $discountAmount;
            $poproduct->taxable_value = $taxableValue;
            $poproduct->taxrate = $taxRate;
            $poproduct->gst_amount = $gstAmount;
            $poproduct->cgst_amount = $cgst;
            $poproduct->igst_amount = $igst;
            $poproduct->sgst_amount = $sgst;
            $poproduct->item_total_amount = $TotalAmount;

            $poproduct->save();

        }
        $request->session()->flash('warning', 'purchase order  updated successfully');
        return redirect()->route('po.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID, Request $request)
    {
        $po = PurchaseOrder::find($ID);
        $status = $message = '';
        if (PurchaseOrder::destroy($po->po_id)) {
            $status = 'error';
            $message = 'purchase order deleted successfully.';
        } else {

            $status = 'info';
            $message = 'purchase order failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('po.index');
    }

    public function getCustomer(Request $request)
    {


        $poID = $request->input('po_id');
        $po = DB::table('purchase_order')->where('po_id', $poID)->first();

        return response()->json(['po_id' => $po->po_id, 'email' => $po->email], 200);
    }



    public function printPO($po_id,$print_type = '')
    {

        $companyprofile = CompanyProfile::with('getcountry')->with('getstate')->first();
        $po = PurchaseOrder::with('customer')->where('po_id', $po_id)->first();


        $customeraddress = CustomerAddress::with('country')->with('state')->first();


        $company_state_name = isset($companyprofile->state_id) ? $companyprofile->state_id : '';

        $placeofsupply= isset($po->place_of_supply) ? $po->place_of_supply : '';


        if ($placeofsupply == $company_state_name) {
            return $this->cgst($po_id,$print_type);
        } else {
            return $this->igst($po_id,$print_type);
        }

    }

    public function igst($po_id,$print_type = ''){

        $po = PurchaseOrder::with('customer')->with('state')->where('po_id', $po_id)->first();

        $customeraddress = CustomerAddress::with('country')->with('state')->first();
        $companyprofile = CompanyProfile::with('getcountry')->with('getstate')->first();

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
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', 'B', '10');
        $fpdf->CellFitScale(202, 10, strtoupper($companyprofile->company_name), 1, 1, 'C', true);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array(202));

        $part1 = strtoupper(!empty($companyprofile->address1)?$companyprofile->address1:'') . "\n" . strtoupper(!empty($companyprofile->address2)?$companyprofile->address2:'') .strtoupper(!empty($companyprofile->address3)?$companyprofile->address3:'')."\n".'COUNTRY:' . strtoupper(!empty($companyprofile->getcountry->country_name) ? $companyprofile->getcountry->country_name : '').', STATE:' . strtoupper(!empty($companyprofile->getstate->state_name) ? $companyprofile->getstate->state_name : '').', CITY:' . strtoupper(!empty($companyprofile->city) ? $companyprofile->city : '').',' . strtoupper(!empty($companyprofile->pincode) ? $companyprofile->pincode : '');

        $fpdf->Row(array($part1), array('C'), '', '', true, 4);


        $fpdf->SetFont('courier', 'B', 12);
        $fpdf->CellFitScale(202, 7, 'PURCHASE ORDER', 1, 1, 'C');
        $fpdf->SetFont('courier', 'B', 11);
        $fpdf->CellFitScale(20, 5, 'M/s. :', 'TL', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper($po->customer->company_name), 'TR', 0, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(30, 5, 'PO No :', 'TL', 0, 'L', true);
        $fpdf->CellFitScale(50, 5, ': '. (!empty($companyprofile->po_prefix)?$companyprofile->po_prefix:'') . str_pad($po->po_id, 3, '0', STR_PAD_LEFT), 'TR', 1, 'L', true);


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address1)?$customeraddress->billing_address1:''), 'R', 0, 'L');


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->CellFitScale(30, 5, 'PO Date', 'LB', 0, 'L', true);
        $fpdf->CellFitScale(50, 5, ': ' . date('d/m/Y', strtotime($po->bill_date)), 'RB', 1, 'L', true);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper($customeraddress->billing_address2), 'R', 0, 'L');



        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(34, 5, 'Place Of Supply:', 'L', 0, 'L', true);
        $fpdf->CellFitScale(46, 5, strtoupper(!empty($po->state->state_name)?$po->state->state_name:''), 'R', 1, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper($customeraddress->billing_address3), 'R', 0, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(50, 5, " " . '', 'R', 1, 'L');

        if (!empty($customeraddress->country->country_name)) {

            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetFont('courier', '', 10);
            $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
            $fpdf->CellFitScale(102, 5, 'COUNTRY:' . strtoupper(!empty($customeraddress->country->country_name) ? $customeraddress->country->country_name : '') . ' ,STATE:' . strtoupper(!empty($customeraddress->state->state_name) ? $customeraddress->state->state_name : '') . ' ,CITY:' . strtoupper(!empty($customeraddress->billing_city) ? $customeraddress->billing_city : '') . strtoupper(!empty($customeraddress->billing_pincode) ? ',' . $customeraddress->billing_pincode : ''), 'R', 0, 'L');

            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetFont('courier', '', 10);
            $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
            $fpdf->CellFitScale(50, 5, " " . '', 'R', 1, 'L');

        }


        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(18, 5, 'GST IN', 'L', 0, 'L');
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(104, 5, ': ' . $po->customer->gst_no, 'R', 0, 'L');
        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(32, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(48, 5, '', 'R', 1, 'L', true);
        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(18, 5, 'PAN No', 'L', 0, 'L');
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(104, 5, ': ' .'', 'R', 0, 'L');
        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(32, 5, '', 'LB', 0, 'L', true);
        $fpdf->CellFitScale(48, 5, '', 'RB', 1, 'L', true);


        $fpdf->SetFont('courier', '', 9);


        $PiItem = PurchaseOrderProduct::with('getItemName')->where('po_id', $po_id)->get();

        $PiProductsDiscount = PurchaseOrderProduct::where('po_id', $po_id)->sum('discount_rate');


        $total_rows = 10;
//        $total_rows -=count($salesProduct);
        $fpdf->SetWidths(array(5, 7, 50, 15, 20, 26));
        $fpdf->SetFont('courier', 'B', '');
        $fpdf->Cell(8, 5, 'NO', 'TL', 0, 'C');
        $fpdf->CellFitScale(54, 5, 'Description of Goods / Service', 'TL', 0, 'C');
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

        $itemTotal = $totalQty = $totalRate = $totalAmount = $totalDiscount = $totalTaxableValue = $totalIgst = $grandTotal = 0;
        $i = 1;
        $itemTotal = 0;
        $POItem = PurchaseOrderProduct::with('getItemName')->where('po_id', $po_id)->get();
        $PiProductsDiscount = PurchaseOrderProduct::where('po_id', $po_id)->sum('discount_rate');
        $i = 1;
        $itemTotal = 0;
        $rowTotal = 28;

        foreach ($POItem as $value) {
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
        $fpdf->Row(array('', '  Sub Total', $totalQty, '', number_format($totalAmount, 2), number_format($totalDiscount, 2), number_format($totalTaxableValue, 2), '', number_format($totalIgst, 2), number_format($grandTotal, 2)), array('C', 'L', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R'), false, '', true);
        $fpdf->SetWidths(array(62, 10, 40, 20, 40, 30));
        $fpdf->SetFont('courier', '', 8);

        $taxInfo = DB::table('purchase_order_product')->selectRaw("max(taxrate) as taxrate")->where('po_id', $po_id)->first();
        $gstTaxRate = $taxInfo->taxrate;
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->CellFitScale(120, 5, 'GSTIN No:'.''.$po->customer->gst_no, 1, 0, 'L', true);
        $fpdf->CellFitScale(50, 5, 'Sub Total', 'TLB', 0, 'L', true);
        $fpdf->CellFitScale(32, 5, number_format($itemTotal, 2), 'TRB', 1, 'R', true);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(25, 5, 'Bank Name :', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_name)?$companyprofile->bank_name:''), 'R', 0, 'L');

        $fpdf->CellFitScale(50, 5, 'P & F ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($po->pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(25, 5, 'Bank A/c. No. : ', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_ac_no)?$companyprofile->bank_ac_no:''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Pf TaxRate ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($po->pf_taxrate), 2), 'R', 1, 'R');

        $fpdf->CellFitScale(25, 5, 'RTGS/IFSC Code :', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_ifsc_code)?$companyprofile->bank_ifsc_code:''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Total With PF ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($po->total_with_pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(25, 5, ' ', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, ' ', 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Rounding ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($po->rounding_amount), 2), 'R', 1, 'R');


        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(15, 5, '', 'LB', 0, 'L');
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(105, 5, '', 'RB', 0, 'L');
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(50, 5, 'Grand Total', 'LBT', 0, 'L', true);
        $fpdf->CellFitScale((32), 5, number_format(round($po->grand_total), 2), 'RBT', 1, 'R', true);;
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array(202));
        $fpdf->Row(array('TOTAL AMOUNT IN WORDS INR: ' . strtoupper($this->conver_num_text_money(round($po->grand_total)))),
            array('L'), false, '', true);


        /* Footer Payment and condition*/
        if (!empty($po->term_condition)) {

            $fpdf->SetFont('courier', 'B', 9);
            $fpdf->CellFitScale(32, 5, "Freight", 'L', 0, 'L');
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->CellFitScale(190, 5, "Terms & Condition", 1, 1, 'L', true);
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetWidths([190]);
            $fpdf->SetFont('courier', 'B', 9);
            $fpdf->Row([$po->term_condition], ['L'], '', '', true, 3);

        }



        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(62, 5, "Dispatch Through", 'L', 0, 'L');
        $fpdf->CellFitScale(140, 5, ":   " . (!empty($po->dispatch_through) ? $po->dispatch_through : ''), 'R', 1, 'L');
        $fpdf->CellFitScale(62, 5, "Delivery Period", 'L', 0, 'L');
        $fpdf->CellFitScale(140, 5, ":   " . (!empty($po->delivery_period) ? $po->delivery_period : ''), 'R', 1, 'L');
        $fpdf->CellFitScale(62, 5, "Payment", 'L', 0, 'L');
        $fpdf->CellFitScale(140, 5, ":   " . (!empty($po->paymentterms->payment_terms) ? $po->paymentterms->payment_terms : ''), 'R', 1, 'L');

        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->CellFitScale(202, 7, '', 'T');
        $fpdf->SetFillColor(255, 255, 255);


        if ($print_type == 'SendEMail') {
            $file_name = $fpdf->Output('', 'S');
            return $file_name;
        } else if($print_type == 'PO') {
            $filename = 'sales_' . date('ymdhis') . '.pdf';
            $fpdf->Output('telegram/' . $filename, 'F');
            return $filename;
        }
        exit();


    }

    public function cgst($po_id,$print_type = '')
    {
        $po = PurchaseOrder::with('customer')->with('state')->where('po_id', $po_id)->first();

        $customeraddress = CustomerAddress::with('country')->with('state')->first();
        $companyprofile = CompanyProfile::with('getcountry')->with('getstate')->first();

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
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', 'B', '12');
        $fpdf->CellFitScale(207, 10, strtoupper($companyprofile->company_name), 1, 1, 'C', true);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetFont('courier', '', 10);

        $fpdf->SetWidths(array(207));

        $part1 = strtoupper(!empty($companyprofile->address1)?$companyprofile->address1:'') . "\n" . strtoupper(!empty($companyprofile->address2)?$companyprofile->address2:'') .strtoupper(!empty($companyprofile->address3)?$companyprofile->address3:'')."\n".'COUNTRY:' . strtoupper(!empty($companyprofile->getcountry->country_name) ? $companyprofile->getcountry->country_name : '').', STATE:' . strtoupper(!empty($companyprofile->getstate->state_name) ? $companyprofile->getstate->state_name : '').', CITY:' . strtoupper(!empty($companyprofile->city) ? $companyprofile->city: '').',' . strtoupper(!empty($companyprofile->pincode) ? $companyprofile->pincode : '');

        $fpdf->Row(array($part1), array('C'), '', '', true, 4);



        $fpdf->SetFont('courier', 'B', 12);
        $fpdf->CellFitScale(207, 7, 'PURCHASE ORDER', 1, 1, 'C');
        $fpdf->SetFont('courier', 'B', 11);
        $fpdf->CellFitScale(20, 5, 'M/s. :', 'TL', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper($po->customer->company_name), 'TR', 0, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(30, 5, 'PO No ', 'TL', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, ': '.(!empty($companyprofile->po_prefix)?$companyprofile->po_prefix:'') . str_pad($po->po_id, 3, '0', STR_PAD_LEFT), 'TR', 1, 'L', true);


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address1)?$customeraddress->billing_address1:''), 'R', 0, 'L');


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->CellFitScale(30, 5, 'PO Date', 'LB', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, ': ' . date('d/m/Y', strtotime($po->bill_date)), 'RB', 1, 'L', true);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address2)?$customeraddress->billing_address2:''), 'R', 0, 'L');


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(30, 5, 'Place Of Supply:', 'L', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, strtoupper(!empty($po->state->state_name)?$po->state->state_name:''), 'R', 1, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address3)?$customeraddress->billing_address3:''), 'R', 0, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);

        $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, " " . '', 'R', 1, 'L');


        if (!empty($customeraddress->country->country_name)) {

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, 'COUNTRY:' . strtoupper(!empty($customeraddress->country->country_name) ? $customeraddress->country->country_name : '') . ' ,STATE:' . strtoupper(!empty($customeraddress->state->state_name) ? $customeraddress->state->state_name : '') . ' ,CITY:' . strtoupper(!empty($customeraddress->billing_city)? $customeraddress->billing_city : '') . strtoupper(!empty($customeraddress->billing_pincode) ? ',' . $customeraddress->billing_pincode : ''), 'R', 0, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, " " . '', 'R', 1, 'L');

    }

        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(20, 5, 'GST IN', 'L', 0, 'L');
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(102, 5, ':' .(!empty( $po->customer->gst_no)?$po->customer->gst_no:'') . ', PAN No: ' . $companyprofile->pan_no, 'R', 0, 'L');
        $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, '', 'R', 1, 'L', true);
        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
            $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(102, 5, ' ' . '', 'R', 0, 'L');
        $fpdf->CellFitScale(30, 5, '', 'LB', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, '', 'RB', 1, 'L', true);


        $PiItem = PurchaseOrderProduct::with('getItemName')->where('po_id', $po_id)->get();

        $PiProductsDiscount = PurchaseOrderProduct::where('po_id', $po_id)->sum('discount_rate');

        $total_rows = 10;
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

        $itemTotal = $totalQty = $totalAmount = $totalDiscount = $totalTaxableValue = $totalSGST = $totalCGST = $grandTotal = 0;
        $salesItem = PurchaseOrderProduct::with('getItemName')->where('po_id', $po_id)->get();
        $salesProductsDiscount = PurchaseOrderProduct::where('po_id', $po_id)->sum('discount_rate');

        $i = 1;
        $rowTotal = 28;
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
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->CellFitScale(122, 5, 'GSTIN No:'.''.$po->customer->gst_no, 1, 0, 'L', true);
        $fpdf->CellFitScale(54, 5, 'Sub Total', 'TLB', 0, 'L', true);
        $fpdf->CellFitScale(31, 5, number_format($itemTotal, 2), 'TRB', 1, 'R', true);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array(62, 10, 40, 20, 40, 30));
        $fpdf->SetWidths(array(202));

        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->CellFitScale(28, 5, 'Bank A/c. No.:', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_ac_no)?$companyprofile->bank_ac_no:''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Tax Rate ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($po->pf_taxrate), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, 'Bank Name :', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_name)?$companyprofile->bank_name:''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'P & F ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($po->pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, 'RTGS/IFSC Code :', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_ifsc_code)?$companyprofile->bank_ifsc_code:''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Total With PF ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($po->total_with_pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, ' ', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, ' ', 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Rounding ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($po->rounding_amount), 2), 'R', 1, 'R');


        $fpdf->SetFont('courier', '', 9);
        $fpdf->CellFitScale(17, 5, '', 'LB', 0, 'L');
        $fpdf->SetFont('courier', '', 9);
        $fpdf->CellFitScale(105, 5, '', 'RB', 0, 'L');
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(48, 5, 'Grand Total', 'LBT', 0, 'L', true);

        $fpdf->CellFitScale((37), 5, number_format(round($po->grand_total), 2), 'RBT', 1, 'R', true);;
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array(207));
        $fpdf->Row(array('TOTAL AMOUNT IN WORDS INR: ' . strtoupper($this->conver_num_text_money(round($po->grand_total)))),
            array('L'), false, '', true);


        /* Footer Payment and condition*/
        if (!empty($po->term_condition)) {

            $fpdf->SetFont('courier', 'B', 9);
            $fpdf->CellFitScale(32, 5, "Freight", 'L', 0, 'L');
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->CellFitScale(190, 5, "Terms & Condition", 1, 1, 'L', true);
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetWidths([190]);
            $fpdf->SetFont('courier', 'B', 9);
            $fpdf->Row([$po->term_condition], ['L'], '', '', true, 3);

        }


        $fpdf->SetFont('courier', 'B', 9);

        $fpdf->CellFitScale(62, 5, "Dispatch Through", 'L', 0, 'L');
        $fpdf->CellFitScale(145, 5, ":   " . (!empty($po->dispatch_through) ? $po->dispatch_through : ''), 'R', 1, 'L');
        $fpdf->CellFitScale(62, 5, "Delivery Period", 'L', 0, 'L');
        $fpdf->CellFitScale(145, 5, ":   " . (!empty($po->delivery_period) ? $po->delivery_period : ''), 'R', 1, 'L');
        $fpdf->CellFitScale(62, 5, "Payment", 'L', 0, 'L');
        $fpdf->CellFitScale(145, 5, ":   " . (!empty($po->paymentterms->payment_terms) ? $po->paymentterms->payment_terms : ''), 'R', 1, 'L');

        $fpdf->CellFitScale(207, 7, '', 'T', 0, 'R', true);
        $fpdf->SetFillColor(255, 255, 255);

        if ($print_type == 'SendEMail') {
            $file_name = $fpdf->Output('', 'S');
            return $file_name;
        } else if($print_type == 'PO') {
            $filename = 'sales_' . date('ymdhis') . '.pdf';
            $fpdf->Output('telegram/' . $filename, 'F');
            return $filename;
        }

        exit();


    }

    public function sendEmail(Request $request)
    {
        $mail_title = $request->input('mail_title');
        $mail_body = $request->input('mail_body');
        $email = $request->input('email');
        $mail_po_id = $request->input('mail_po_id');
        $attachment = $request->input('attachment');
        $type = 'PurchaseOrder';

        if ($attachment == 'on') {
            $file_name = $this->printPO($mail_po_id, 'SendEMail');
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
        return redirect()->route('po.index');
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

}
