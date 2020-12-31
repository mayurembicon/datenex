<?php

namespace App\Http\Controllers;

use App\CompanyProfile;
use App\Customer;
use App\DocketDeteils;
use App\Financial_Year;
use App\FinancialYear;
use App\FollowUp;
use App\Inquiry;
use App\InquiryProduct;
use App\Invoice;
use App\Notifications\TelegramNotification;
use App\QuotationPayment;
use App\SalesBillingAddress;
use App\InvoiceItems;
use App\SalesItems;
use App\SalesShippingAddress;
use App\Item;
use App\Library\FPDFExtensions;
use App\PaymentTerms;
use App\Pi;
use App\PiBillingAddress;
use App\PiItem;
use App\PiShippingAddress;
use App\Quotation;
use App\QuotationBillingAddress;
use App\QuotationProductDetail;
use App\QuotationShippingAddress;
use App\Sales;
use App\Setting;
use App\TaxRate;
use App\TransportDeteils;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

use PHPUnit\Runner\Filter\IncludeGroupFilterIterator;

class PiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)

    {
        $setting = Setting::first();
        $selectedPiNo = '';
        $selectedOrderNo = '';
        $selectedCustomer = '';
        $selectedItem = '';
        $search_item = $request->session()->get('search_pi');
        $transportdeteils = TransportDeteils::all();
        $docketdeteils = DocketDeteils::all();
        $taxrate = TaxRate::all();
        $item = Item::all();
        $customers = Customer::all();

        $piitems = PiItem::all();


        $queryObject = DB::table('pi')
            ->leftjoin('customer', 'customer.customer_id', '=', 'pi.customer_id')
            ->select(['pi.pi_id', 'pi.pi_no', 'pi.order_no', 'pi.pi_date', 'pi.due_date', 'customer.company_name', 'pi.sales_status']);
        if (!empty($search_item['pi_no'])) {
            $queryObject->whereRaw("`pi_no` LIKE '%" . $search_item['pi_no'] . "%'");
            $selectedPiNo = $search_item['pi_no'];

        }
        if (!empty($search_item['order_no'])) {
            $queryObject->whereRaw("`order_no` LIKE '%" . $search_item['order_no'] . "%'");
            $selectedOrderNo = $search_item['order_no'];

        }
        if (!empty($search_item['company_name'])) {
            $queryObject->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
        if (!empty($search_item['name'])) {
            $queryObject->join('pi_items', 'pi_items.pi_id', '=', 'pi.pi_id');
            $queryObject->join('itemmaster', 'itemmaster.item_id', '=', 'pi_items.item_id');
            $queryObject->whereRaw("`name` LIKE '%" . $search_item['name'] . "%'");
            $selectedItem = $search_item['name'];
        }

        $queryObject->get();
        $pi = $queryObject->paginate(10);
        return view('pi.index')->with(compact('customers', 'taxrate', 'transportdeteils', 'docketdeteils', 'piitems', 'pi', 'item', 'search_item', 'selectedItem', 'selectedCustomer', 'selectedPiNo', 'selectedOrderNo', 'setting'));
    }

    public function searchPi(Request $request)
    {
        $search = array();
        $search['pi_no'] = $request->post('pi_no');
        $search['order_no'] = $request->post('order_no');
        $search['company_name'] = $request->post('customer_id');
        $search['name'] = $request->post('item_id');

        $request->session()->put('search_pi', $search);
        return redirect()->route('pi.index');
    }

    public function clearPi(Request $request)
    {
        $request->session()->forget('search_pi');
        return redirect()->route('pi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($quotation_id = null)
    {
        $pi = Quotation::find($quotation_id);
        $taxrate = TaxRate::all();
        $payment = PaymentTerms::all();
        $customers = Customer::all();
        $item = Item::all();

        $piitems = [];

        if ($quotation_id) {

            $piitemsArray = QuotationProductDetail::with('getItemName')->where('quotation_id', $pi->quotation_id)->get();

            $piitems = [];

            foreach ($piitemsArray as $Items) {
                array_push($piitems, [
                    'quotation_id' => $Items->quotation_id,
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

        return view('pi.create')->with(compact('customers', 'item', 'payment', 'quotation_id', 'taxrate', 'pi', 'piitems'))->with('TY', 'I');
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
            'customer_id.required' => 'Please Select Company Name',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();


        $piNo = Pi::max('pi_no');
        $piNo += 1;

        $pi = new Pi();
        $pi->customer_id = $request->post('customer_id');
        $pi->financial_year_id = $financial->financial_year_id;
        $pi->quotation_id = $request->post('quotation_id');
        $pi->pi_no = $piNo;
        $pi->order_no = $request->post('order_no');
        $pi->ref_order_no = $request->post('ref_order_no');
        $pi->ref_order_date = date('Y-m-d ', strtotime($request->post('ref_order_date')));
        $pi->email = $request->post('email');
        $pi->pi_date = date('Y-m-d ', strtotime($request->post('pi_date')));
        $pi->due_date = date('Y-m-d ', strtotime($request->post('due_date')));
        $pi->payment_terms_id = $request->post('payment_terms_id');
        $pi->pi_person = $request->post('pi_person');
        $pi->notes = $request->post('notes');
        $pi->total = $request->post('total');
        $pi->created_id = Auth::user()->id;
        $pi->save();
        if ($pi->quotation_id) {
            Quotation::where('quotation_id', $pi->quotation_id)->update(['quotation_status' => 'Pi Created']);
        }

        $piID = $pi->pi_id;
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
            $piitems = new PiItem();
            $piitems->pi_id = $piID;
            $piitems->item_id = $item['item_id'];
            $piitems->p_description = $item['p_description'];
            $piitems->qty = $qty;
            $piitems->rate = $rate;
            $piitems->discount_rate = $discountRate;
            $piitems->discount_type = 'p';
            $piitems->discount_amount = $discountAmount;
            $piitems->taxable_value = $taxableValue;
            $piitems->taxrate = $taxRate;
            $piitems->gst_amount = $gstAmount;
            $piitems->cgst_amount = $cgst;
            $piitems->igst_amount = $igst;
            $piitems->sgst_amount = $sgst;
            $piitems->item_total_amount = $TotalAmount;
            $piitems->save();

        }
        $itemSumTotal = DB::table('pi_items')->where('pi_id', $piID)->sum('item_total_amount');
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
        Pi::where('pi_id', $piID)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);

        $docketdeteils = new DocketDeteils();
        $docketdeteils->pi_id = $piID;
        $docketdeteils->delivery_location = $request->post('delivery_location');
        $docketdeteils->courier_name = $request->post('courier_name');
        $docketdeteils->tracking_no = $request->post('tracking_no');
        $docketdeteils->save();

        $transportdeteils = new TransportDeteils();
        $transportdeteils->pi_id = $piID;
        $transportdeteils->desp_through = $request->post('desp_through');
        $transportdeteils->transport_id = $request->post('transport_id');
        $transportdeteils->lorry_no = $request->post('lorry_no');
        $transportdeteils->lr_no = $request->post('lr_no');
        $transportdeteils->lr_date = date('Y-m-d ', strtotime($request->post('lr_date')));
        $transportdeteils->place_of_supply = $request->post('place_of_supply');
        $transportdeteils->save();


        $billing_address = new PiBillingAddress();
        $billing_address->pi_id = $piID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->city_name = $request->post('city_name');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();

        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address = new PiShippingAddress();
            $shipping_address->pi_id = $piID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $billing_address->city_name = $request->post('city_name');
            $shipping_address->shipping_pincode = $request->post('zip_code');
            $shipping_address->shipping_address = $request->post('address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address = new PiShippingAddress();
            $shipping_address->pi_id = $piID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->city_name = $request->post('shipping_city_name');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }

        $request->session()->flash('success', 'Proforma Invoice created successfully');
        return redirect()->route('pi.index');
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

    public function sendTelegram(Request $request, $quotation_id)
    {

        $userInfo = DB::table('users')->where('id', Auth::user()->id)->first();
        $telegramID = $userInfo->telegram_id;
        $file_name = $this->printPI($quotation_id, 'PI');
        Notification::route('telegram', $telegramID)
            ->notify(new TelegramNotification($quotation_id, 'PI', $file_name));
        unlink('./telegram/' . $file_name);
        $request->session()->flash('success', 'Message Sent Successfully..');
        return redirect()->route('pi.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {
        $pi = Pi::find($ID);
        $payment = PaymentTerms::all();
        $item = Item::find($ID);
        $billing_address = PiBillingAddress::where('pi_id', $pi->pi_id)->first();
        $shipping_address = PiShippingAddress::where('pi_id', $pi->pi_id)->first();
        $taxrate = TaxRate::all();
        $docketdeteils = DocketDeteils::where('pi_id', $pi->pi_id)->first();
        $transportdeteils = TransportDeteils::where('pi_id', $pi->pi_id)->first();
        $piitemsArray = PiItem::with('getItemName')->where('pi_id', $pi->pi_id)->get();


        $piitems = [];


        foreach ($piitemsArray as $Items) {
            array_push($piitems, [
                'pi_id' => $Items->pi_id,
                'pi_items_id' => $Items->pi_items_id,
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
        return view('pi.create')->with(compact('shipping_address', 'billing_address', 'taxrate', 'pi', 'transportdeteils', 'docketdeteils', 'item', 'piitems', 'payment'))->with('TY', 'U');

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
            'customer_id.required' => 'Please Select Company Name',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $pi = Pi::find($ID);
        $pi->customer_id = $request->post('customer_id');
        $pi->quotation_id = $request->post('quotation_id');
        $pi->pi_no = $request->post('pi_no');
        $pi->order_no = $request->post('order_no');
        $pi->email = $request->post('email');
        $pi->ref_order_no = $request->post('ref_order_no');
        $pi->ref_order_date = date('Y-m-d ', strtotime($request->post('ref_order_date')));
        $pi->pi_date = date('Y-m-d', strtotime($request->post('pi_date')));
        $pi->due_date = date('Y-m-d', strtotime($request->post('due_date')));
        $pi->payment_terms_id = $request->post('payment_terms_id');
        $pi->pi_person = $request->post('pi_person');
        $pi->notes = $request->post('notes');
        $pi->total = $request->post('total');
        $pi->updated_id = Auth::user()->id;
        $pi->save();
        $piID = $pi->pi_id;


        $PiID = $pi->pi_id;

        $billing_address = PiBillingAddress::find($ID);
        $billing_address->pi_id = $piID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->city_name = $request->post('city_name');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();

        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address = PiShippingAddress::find($ID);
            $shipping_address->pi_id = $piID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $shipping_address->city_name = $request->post('city_name');
            $shipping_address->shipping_pincode = $request->post('zip_code');
            $shipping_address->shipping_address = $request->post('address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address = PiShippingAddress::find($ID);
            $shipping_address->pi_id = $piID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->city_name = $request->post('shipping_city_name');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }


        DocketDeteils::where('pi_id', $ID)->update(['delivery_location' => $request->post('delivery_location'), 'courier_name' => $request->post('courier_name'), 'tracking_no' => $request->post('tracking_no')]);

        TransportDeteils::where('pi_id', $ID)->update(['desp_through' => $request->post('desp_through'), 'transport_id' => $request->post('transport_id'), 'lorry_no' => $request->post('lorry_no'), 'lr_no' => $request->post('lr_no'), 'lr_date' => $request->post('lr_date'), 'place_of_supply' => $request->post('place_of_supply')]);


        PiItem::where('pi_id', $pi->pi_id)->delete();
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
            $piitems = new PiItem();
            $piitems->pi_id = $piID;
            $piitems->item_id = $item['item_id'];
            $piitems->p_description = $item['p_description'];
            $piitems->qty = $qty;
            $piitems->rate = $rate;
            $piitems->discount_rate = $discountRate;
            $piitems->discount_type = 'p';
            $piitems->discount_amount = $discountAmount;
            $piitems->taxable_value = $taxableValue;
            $piitems->taxrate = $taxRate;
            $piitems->gst_amount = $gstAmount;
            $piitems->cgst_amount = $cgst;
            $piitems->igst_amount = $igst;
            $piitems->sgst_amount = $sgst;
            $piitems->item_total_amount = $TotalAmount;
            $piitems->save();

        }

        $itemSumTotal = DB::table('pi_items')->where('pi_id', $piID)->sum('item_total_amount');
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
        Pi::where('pi_id', $piID)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);

        $request->session()->flash('warning', 'Proforma Invoice updated successfully');
        return redirect()->route('pi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pi $pi)
    {
        if ($pi->quotation_id) {
            Quotation::where('quotation_id', $pi->quotation_id)->update(['quotation_status' => 'Pending']);
        }
        DB::table('pi_shipping_address')->where('pi_id', $pi->pi_id)->delete();
        DB::table('pi_billing_address')->where('pi_id', $pi->pi_id)->delete();
        $status = $message = '';
        if (Pi::destroy($pi->pi_id)) {
            $status = 'error';
            $message = 'Proforma Invoice deleted successfully.';
        } else {

            $status = 'info';
            $message = 'Proforma Invoice failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('pi.index');
    }

    public function getPiCustomer(Request $request)
    {


        $piID = $request->input('pi_id');

        $pi = DB::table('pi')
            ->join('customer', 'customer.customer_id', '=', 'pi.customer_id')
            ->select(['customer.company_name', 'pi.pi_id', 'pi.customer_id', 'pi.pi_person'])
            ->where('pi.pi_id', $piID)
            ->first();


        return response()->json(['pi_id' => $pi->pi_id, 'customer_name' => $pi->company_name, 'contact_person' => $pi->pi_person,], 200);
    }

    public function savePi(Request $request)
    {

        $messages = [
            'remark.required' => 'Please Enter Remark',
            'next_followup_date.required' => 'Please Enter Date'
        ];
        $rules = [
            'remark' => 'required',
            'next_followup_date' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if (!$request->ajax()) {
            $validator->validate();
        } else {
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            }
        }

        $followup = new FollowUp();
        $followup->pi_id = $request->input('pi_id');
        $followup->created_id = Auth::user()->id;
        $followup->remark = $request->input('remark');
        $followup->next_followup_date = date('Y-m-d ', strtotime($request->post('next_followup_date')));

        $followup->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Follow Up successfully.', 'followup' => ['remark' => $followup->remark, 'next_followup_date' => $followup->next_followup_date, 'pi_id' => $followup->pi_id]]);
        } else {
            $request->session()->flash('success', 'Follow Up successfully');
            return redirect()->route('quotation.index');
        }
    }

    public function Pitimeline($pi_id)
    {

        $followup = DB::table('followup')
            ->join('users', 'users.id', '=', 'followup.created_id')
            ->select(['users.name', 'followup.remark', 'followup.created_at'])
            ->where('followup.pi_id', $pi_id)
            ->get();


        return view('Inquiry.followup')->with(compact('followup', 'pi_id'));
    }

    public function getTimeline(Request $request)
    {


        $piID = $request->input('pi_id');
        $followup = DB::table('followup')
            ->join('users', 'users.id', '=', 'followup.created_id')
            ->select(['users.name', 'followup.remark', DB::raw("date_format(followup.created_at,'%d-%m-%Y %r')as created_at ")])
            ->where('followup.pi_id', $piID)
            ->get()->toArray();


        return response()->json([$followup], 200);
    }

    public function getPiTransaction(Request $request)
    {
        $pi = Pi::with('customer')->with('createdBy')->where('pi_id', $request->input('piID'))->first();
        $piProducts = PiItem::with('getItemName')->where('pi_id', $request->input('piID'))->get();
        $html = '


<table class="table table-bordered table-sm">
   <tr>
    <th>Company Name</th>
    <td>' . $pi->customer->company_name . '</td>
  </tr>
  <tr>
    <th>Date</th>
    <td>' . date('d-m-Y', strtotime($pi->pi_date)) . '</td>
  </tr>

  <tr>
    <th>Due Date</th>
    <td>' . date('d-m-Y', strtotime($pi->pi_date)) . '</td>
  </tr>
  <tr>
    <th>Pi Person </th>
    <td> ' . $pi->pi_person . '</td>
  </tr>
   <tr>
    <th>Order No </th>
    <td> ' . $pi->order_no . '</td>
  </tr>
  <tr>
    <th>Pi No  </th>
    <td> ' . $pi->pi_no . '</td>
  </tr>

</table>

                                            <div class="k-separator k-separator--border-dashed"></div>
                                            <div class="k-separator k-separator--height-sm"></div>
                                            <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                            <thead>
                                            <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Product Description</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Rate</th>

                                                <th class="text-center">Discount (' . ($piProducts[0]->discount_type) . ')</th>
                                            <th class="text-center">Taxable Value</th>
                                            <th class="text-center">Taxable Rate</th>
                                            <th class="text-center">CGST</th>
                                            <th class="text-center">SGST</th>
                                            <th class="text-center">IGST</th>
                                            <th class="text-center">Total</th>

</tr>
</thead>
<tbody>';
        $counter = 1;
        foreach ($piProducts as $piProduct) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $counter . '</td>';
            $html .= '<td>' . (!empty($piProduct->getItemName->name) ? $piProduct->getItemName->name : '') . '</td>';
            $html .= '<td>' . $piProduct->p_description . '</td>';
            $html .= '<td class="text-center">' . $piProduct->qty . '</td>';
            $html .= '<td class="text-center">' . $piProduct->rate . '</td>';
            $html .= '<td class="text-center">' . $piProduct->discount_rate . '</td>';

            $html .= '<td class="text-center">' . number_format($piProduct->taxable_value, 2) . '</td>';
            $html .= '<td class="text-center">' . $piProduct->taxrate . '</td>';
            $html .= '<td class="text-center">' . number_format($piProduct->cgst_amount, 2) . '</td>';
            $html .= '<td class="text-center">' . number_format($piProduct->sgst_amount, 2) . '</td>';
            $html .= '<td class="text-center">' . number_format($piProduct->igst_amount, 2) . '</td>';
            $html .= '<td class="text-center">' . number_format($piProduct->item_total_amount, 2) . '</td>';

            $html .= '</tr>';
            $counter++;
        }
        $html .= '</tbody></table></div>

<table class="table table-bordered table-sm ">
  <tr>
    <th>Total</th>
    <td>' . $pi->total . '</td>
  </tr>
 <tr>
        <th>P & F / Freight</th>
    <td> ' . $pi->pf . '</td>
  </tr>

   <tr>
        <th>Tax Rate</th>
    <td> ' . $pi->pf_taxrate . '</td>
  </tr>

  <tr>
    <th>Total PF  </th>
    <td> ' . $pi->total_with_pf . '</td>
  </tr>
  <tr>
    <th>Rounding  </th>
    <td> ' . $pi->rounding_amount . '</td>
  </tr>
   <tr>
    <th>Grand Total  </th>
    <td> ' . $pi->grand_total . '</td>
  </tr>

</table>

                                            <div class="form-group row">
                                                <label class="col-10 col-form-label col-form-label-sm">Notes  : ' . $pi->notes . '</label>
                                            </div>
                                            ';

        return response($html);
    }


    public function printPI($piID, $print_type = '')
    {
        $companyprofile = CompanyProfile::first();
        $pitransport = TransportDeteils::with('state')->where('pi_id', $piID)->first();
        $placeofsupply = isset($pitransport->state->state_name) ? $pitransport->state->state_name : '';

        $company_state_name = isset($companyprofile->getstate->state_name) ? $companyprofile->getstate->state_name : '';

        if ($placeofsupply == $company_state_name) {
            return $this->cgst($piID, $print_type);
        } else {
            return $this->igst($piID, $print_type);
        }

    }

    public function igst($piID, $print_type = '')
    {

        $companyprofile = CompanyProfile::first();
        $pi = Pi::with('customer')->where('pi_id', $piID)->first();
        $pitransport = TransportDeteils::with('state')->where('pi_id', $piID)->first();
        $pibillingAddress = PiBillingAddress::with('country')->with('state')->where('pi_id', $piID)->first();
        $piShippingAddress = PiShippingAddress::with('country')->with('state')->where('pi_id', $piID)->first();

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
        $fpdf->CellFitScale(35, 5, 'PERFORMA INVOICE', 0, 0, 'C');
        $fpdf->CellFitScale(75, 5, 'ORIGINAL/ DUPLICATE/ TRIPLICATE/ EXTRA', 0, 0, 'R');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(101, 4, 'Supplier (BILL FROM)', 'TRL', 0);

        $fpdf->CellFitScale(47, 4, 'Invoice No:' . $companyprofile->invoice_prefix . $pi->pi_no, 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, 'Date : ' . date('d/m/Y', strtotime($pi->pi_date)), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(101, 4, strtoupper($companyprofile->company_name), 'RL', 0, 'L');
        $fpdf->CellFitScale(47, 4, 'Desp.Through :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $pitransport->desp_through, 1, 0, 'L');

        $fpdf->Ln();
        $fpdf->SetFont('courier', '', '8');
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(95, 4, strtoupper($companyprofile->address1), '', 0);
        $fpdf->CellFitScale(47, 4, 'Transport GST/ID :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $pitransport->transport_id, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(95, 5, strtoupper($companyprofile->address2), '', 0, 'L');
        $fpdf->CellFitScale(47, 4, 'LR No :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $pitransport->lr_no, 1, 0, 'L');
        $fpdf->Ln();

        if (date('d-m-Y', strtotime($pi->pi_date) != "01-01-1970" && !empty($salestransport->lr_date))) {
            $lr_date = date('d/m/Y', strtotime($pitransport->lr_date));
        } else {
            $lr_date = '';
        }
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(95, 5, strtoupper($companyprofile->address3), '', 0, 'L');
        $fpdf->CellFitScale(47, 4, 'LR Date : ', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $lr_date, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', '8');
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(95, 4, 'COUNTRY:' . strtoupper(!empty($companyprofile->getcountry->country_name) ? $companyprofile->getcountry->country_name : '') . ', STATE:' . strtoupper(!empty($companyprofile->getstate->state_name) ? $companyprofile->getstate->state_name : '') . ', CITY:' . strtoupper(!empty($companyprofile->city) ? $companyprofile->city : '') . ',' . strtoupper(!empty($companyprofile->pincode) ? $companyprofile->pincode : ''), '', 0, 'L');

        $fpdf->CellFitScale(47, 4, 'Lorry No :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $pitransport->lorry_no, 1, 0, 'L');
        $fpdf->Ln();

        $fpdf->CellFitScale(101, 4, '', 'RL', 0);
        $fpdf->CellFitScale(47, 4, 'Place of Supply :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, (!empty($pitransport->state->state_name)?$pitransport->state->state_name:''), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(101, 4, 'PH NO. :' . $companyprofile->phone_no, 'RL', 0);
        $fpdf->CellFitScale(47, 4, 'Ref Order No   :', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, $pi->ref_order_no, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(101, 4, 'GST IN.:' . $companyprofile->gst_in, 'RLB', 0);
        $fpdf->CellFitScale(47, 4, 'Ref Order Date :' . '', 1, 0, 'L');
        $fpdf->CellFitScale(54, 4, date('d/m/Y', strtotime($pi->ref_order_date)), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->SetFont('courier', 'B', '8');
        $fpdf->CellFitScale(101, 4, 'Buyer (BILL TO)', 'TRL', 0);
        $fpdf->CellFitScale(101, 4, 'Recipient (SHIP TO)', 'TRL', 0, 'L');
        $fpdf->SetFont('courier', '', '8');
        $fpdf->Ln();
        $fpdf->SetWidths(array(101, 101));

        $part1 = $pi->customer->company_name . "\n" . $pibillingAddress->address . "\n" . (!empty($pibillingAddress->zip_code) ? ' - ' . $pibillingAddress->zip_code : '') . ', State : ' . (!empty($pibillingAddress->state->state_name) ? $pibillingAddress->state->state_name : '') . ', City : ' . (!empty($pibillingAddress->city_name) ? $pibillingAddress->city_name : '') . ', Country : ' . (!empty($pibillingAddress->country->country_name) ? $pibillingAddress->country->country_name : '') . "\n" . 'PAN NO.: ' . 'AACFP7930Q' . "\n" . 'GST IN  :' . (!empty($pi->customer->gst_no)?$pi->customer->gst_no:'') . "\n" . '  ' . '' . ' ' . '';


        $part2 = $pi->customer->company_name . "\n" . $piShippingAddress->shipping_address . "\n" . (!empty($piShippingAddress->shipping_pincode) ? ' - ' . $piShippingAddress->shipping_pincode : '') . ', State : ' . (!empty($piShippingAddress->state->state_name) ? $piShippingAddress->state->state_name : ''). ', City : ' . (!empty($piShippingAddress->city_name) ? $piShippingAddress->city_name : '') . ', Country : ' . (!empty($piShippingAddress->country->country_name) ? $piShippingAddress->country->country_name : '') . "\n" . 'PAN NO.: ' . 'AACFP7930Q' . "\n" . 'GST IN  :'. (!empty($pi->customer->gst_no)?$pi->customer->gst_no:'') .  "\n" . '  ' . '' . ' ' . '';

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
        $piItem = PiItem::with('getItemName')->where('pi_id', $piID)->get();
        $salesProductsDiscount = PiItem::where('pi_id', $piID)->sum('discount_rate');


        $rowTotal = 25;
        foreach ($piItem as $value) {
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
        $fpdf->SetWidths(array(202));
        $fpdf->SetFont('courier', '', 8);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->CellFitScale(25, 5, 'Bank A/c. No. :', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_ac_no)?$companyprofile->bank_ac_no:''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Tax Rate ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($pi->pf_taxrate), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(25, 5, 'Bank Name :', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_name)?$companyprofile->bank_name:''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'P & F ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($pi->pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(25, 5, 'RTGS/IFSC Code :', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_ifsc_code)?$companyprofile->bank_ifsc_code:''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Total With PF ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($pi->total_with_pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(25, 5, ' ', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, ' ', 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Rounding ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($pi->rounding_amount), 2), 'R', 1, 'R');


        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(15, 5, '', 'LB', 0, 'L');
        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(105, 5, '', 'RB', 0, 'L');
        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(50, 5, 'Grand Total', 'LBT', 0, 'L', true);
        $fpdf->CellFitScale((32), 5, number_format(round($pi->grand_total), 2), 'RBT', 1, 'R', true);;
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->Row(array('TOTAL AMOUNT IN WORDS INR: ' . strtoupper($this->conver_num_text_money(round($pi->grand_total)))),
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
        } else if($print_type == 'PI') {
            $filename = date('ymdhis') . '.pdf';
            $fpdf->Output('telegram/' . $filename, 'F');
            return $filename;
        }
        else{
            $fpdf->Output();
        }
        exit();





    }


    public function cgst($piID, $print_type = '')
    {

        $companyprofile = CompanyProfile::first();
        $pi = Pi::with('customer')->where('pi_id', $piID)->first();
        $pitransport = TransportDeteils::with('state')->where('pi_id', $piID)->first();
        $pidocket = DB::table('docket_deteils')->where('pi_id', $piID)->first();
        $pibillingAddress = PiBillingAddress::with('country')->with('state')->where('pi_id', $piID)->first();
        $piShippingAddress = PiShippingAddress::with('country')->with('state')->where('pi_id', $piID)->first();

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
        $fpdf->CellFitScale(35, 5, 'PERFORMA INVOICE', 0, 0, 'C');
        $fpdf->CellFitScale(75, 5, 'ORIGINAL/ DUPLICATE/ TRIPLICATE/ EXTRA', 0, 0, 'R');
        $fpdf->Ln();
        $fpdf->SetFont('courier', 'B', '8');
        $fpdf->CellFitScale(103.5, 4, 'Supplier (BILL FROM)', 'TRL', 0);
        $fpdf->SetFont('courier', '', '8');
        $fpdf->CellFitScale(47.5, 4, 'PI No:' . $pi->pi_no, 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, 'Date : ' . date('d/m/Y', strtotime($pi->pi_date)), 1, 0, 'L');
        $fpdf->Ln();

        $fpdf->CellFitScale(103.5, 4, $companyprofile->company_name, 'RL', 0);
        $fpdf->CellFitScale(47.5, 4, 'Desp.Through :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $pitransport->desp_through, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', '8');
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(97.4, 4, strtoupper($companyprofile->address1), '', 0);
        $fpdf->CellFitScale(47.5, 4, 'Transport GST/ID :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $pitransport->transport_id, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(97.4, 4, $companyprofile->address2, '', 0);
        $fpdf->CellFitScale(47.5, 4, 'LR No :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $pitransport->lr_no, 1, 0, 'L');
        $fpdf->Ln();


        if (date('d-m-Y', strtotime($pi->pi_date) != "01-01-1970" && !empty($pitransport->lr_date))) {
            $lr_date = date('d/m/Y', strtotime($pitransport->lr_date));
        } else {
            $lr_date = '';

        }
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(97.4, 4, $companyprofile->address3, '', 0);
        $fpdf->CellFitScale(47.5, 4, 'LR Date :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $lr_date, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(6, 5, '', 'L ', 0, 'L');
        $fpdf->CellFitScale(97.4, 4, 'COUNTRY:' . strtoupper(!empty($companyprofile->getcountry->country_name) ? $companyprofile->getcountry->country_name : '') . ', STATE:' . strtoupper(!empty($companyprofile->getstate->state_name) ? $companyprofile->getstate->state_name : '') . ', CITY:' . strtoupper(!empty($companyprofile->city) ? $companyprofile->city : '') . ',' . strtoupper(!empty($companyprofile->pincode) ? $companyprofile->pincode : ''), '', 0, 'L');

        $fpdf->CellFitScale(47.5, 4, 'Lorry No :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $pitransport->lorry_no, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(103.5, 4, 'PH NO. :' . $companyprofile->phone_no, 'RL', 0);
        $fpdf->CellFitScale(47.5, 4, 'Place of Supply :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, (!empty($pitransport->state->state_name)?$pitransport->state->state_name:''), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(103.5, 4, 'PAN NO.: ' . 'AABCT6162E ', 'RL', 0);
        $fpdf->CellFitScale(47.5, 4, 'Ref Order No :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, $pi->ref_order_no, 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->CellFitScale(103.5, 4, 'GST IN.:' . $companyprofile->gst_in, 'RLB', 0);
        $fpdf->CellFitScale(47.5, 4, 'Ref Order Date :', 1, 0, 'L');
        $fpdf->CellFitScale(56, 4, date('d/m/Y', strtotime($pi->ref_order_date)), 1, 0, 'L');
        $fpdf->Ln();
        $fpdf->SetFont('courier', 'B', '8');
        $fpdf->CellFitScale(103.5, 4, 'Buyer (BILL TO)', 'TRL', 0);
        $fpdf->CellFitScale(103.5, 4, 'Recipient (SHIP TO)', 'TRL', 0, 'L');
        $fpdf->SetFont('courier', '', '8');
        $fpdf->Ln();
        $fpdf->SetWidths(array(103.5, 103.5));


        $part1 = $pi->customer->company_name . "\n" . $pibillingAddress->address . "\n" . (!empty($pibillingAddress->zip_code) ? ' - ' . $pibillingAddress->zip_code : '') . ', State : ' . (!empty($pibillingAddress->state->state_name) ? $pibillingAddress->state->state_name : ''). ', City : ' . (!empty($pibillingAddress->city_name) ? $pibillingAddress->city_name : '') . ', Country : ' . (!empty($pibillingAddress->country->country_name) ? $pibillingAddress->country->country_name : '') . "\n" . 'PAN NO.: ' . 'AACFP7930Q' . "\n" . 'GST IN  :' . (!empty($pi->customer->gst_no)?$pi->customer->gst_no:'') . "\n" . '  ' . '' . ' ' . '';


        $part2 = $pi->customer->company_name . "\n" . $piShippingAddress->shipping_address . "\n" . (!empty($piShippingAddress->shipping_pincode) ? ' - ' . $piShippingAddress->shipping_pincode : '') . ', State : ' . (!empty($piShippingAddress->state->state_name) ? $piShippingAddress->state->state_name : '') . ', City : ' . (!empty($piShippingAddress->city_name) ? $piShippingAddress->city_name : '') . ', Country : ' . (!empty($piShippingAddress->country->country_name) ? $piShippingAddress->country->country_name : '') . "\n" . 'PAN NO.: ' . 'AACFP7930Q' . "\n" . 'GST IN  :'. (!empty($pi->customer->gst_no)?$pi->customer->gst_no:'') .  "\n" . '  ' . '' . ' ' . '';


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
        $piItem = PiItem::with('getItemName')->where('pi_id', $piID)->get();
        $salesProductsDiscount = PiItem::where('pi_id', $piID)->sum('discount_rate');


        $rowTotal = 23;
        foreach ($piItem as $value) {
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

        $fpdf->CellFitScale(28, 5, 'Bank A/c. No. : ', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_ac_no)?$companyprofile->bank_ac_no:''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Tax Rate ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($pi->pf_taxrate), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, 'Bank Name :', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_name)?$companyprofile->bank_name:''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'P & F ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($pi->pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, 'RTGS/IFSC Code :', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_ifsc_code)?$companyprofile->bank_ifsc_code:''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Total With PF ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($pi->total_with_pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, ' ', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, ' ', 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Rounding ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($pi->rounding_amount), 2), 'R', 1, 'R');


        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(17, 5, '', 'LB', 0, 'L');
        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(105, 5, '', 'RB', 0, 'L');
        $fpdf->SetFont('courier', '', 8);
        $fpdf->CellFitScale(48, 5, 'Grand Total', 'LBT', 0, 'L', true);
        $fpdf->CellFitScale((37), 5, number_format(round($pi->grand_total), 2), 'RBT', 1, 'R', true);;
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array(207));
        $fpdf->Row(array('TOTAL AMOUNT IN WORDS INR: ' . strtoupper($this->conver_num_text_money(round($pi->grand_total)))),
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
        } else if($print_type == 'PI') {
            $filename = date('ymdhis') . '.pdf';
            $fpdf->Output('telegram/' . $filename, 'F');
            return $filename;
        }
        else{
            $fpdf->Output();
        }
        exit();




    }


    public function getCustomer(Request $request)
    {


        $piID = $request->input('pi_id');

        $pi = DB::table('pi')->where('pi_id', $piID)
            ->first();


        return response()->json(['pi' => $pi->pi_id, 'email' => $pi->email], 200);
    }

    public function sendEmail(Request $request)
    {
        $mail_title = $request->input('mail_title');
        $mail_body = $request->input('mail_body');
        $email = $request->input('email');
        $type = 'ProformaInvoice';

        $mail_pi_id = $request->input('mail_pi_id');
        $attachment = $request->input('attachment');
        if ($attachment == 'on') {

            $file_name = $this->printPI($mail_pi_id, 'SendMail');
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
        return redirect()->route('pi.index');
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
