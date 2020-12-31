<?php

namespace App\Http\Controllers;

use App\Assign;
use App\Customer;
use App\FinancialYear;
use App\FollowUp;
use App\Inquiry;
use App\InquiryProduct;
use App\InquiryProducts;
use App\Invoice;
use App\InvoiceItems;
use App\Item;
use App\PaymentTerms;
use App\Quotation;
use App\TaxRate;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\PreInc;
use App\Notifications\TelegramNotification;
use Illuminate\Support\Facades\Notification;
use Codedge\Fpdf\Fpdf\Fpdf;


class InquiryController extends Controller
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
        $selectedContactPer = '';
        $selectedSubject = '';
        $selectedEmail = '';
        $selectedPhone = '';
        $search_item = $request->session()->get('search_item');
        $user = User::all();
        $customers = Customer::all();
        $item = Item::all();
        $unit=Unit::all();
        $inquiryproductitems = InquiryProduct::all();

        $queryObject = DB::table('assign')
            ->join('inquiry', 'inquiry.inquiry_id', '=', 'assign.inquiry_id')
            ->join('customer', 'customer.customer_id', '=', 'inquiry.customer_id')
            ->select([
                'inquiry.inquiry_id',
                'inquiry.date',
                'inquiry.subject',
                'inquiry.ratedIndex',
                'inquiry.inquiry_from',
                'inquiry.contact_person',
                'inquiry.inquiry_status',
                'inquiry.phone_no',
                'inquiry.email',
                'customer.company_name',
            ]);
        $queryObject->where('assign.user_id', Auth::user()->id);
        $queryObject->groupBy('inquiry.inquiry_id','assign.assign_id');
        if (!empty($search_item['company_name'])) {
            $queryObject->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
        if (!empty($search_item['name'])) {
            $queryObject->join('inquiry_product', 'inquiry_product.inquiry_id', '=', 'inquiry.inquiry_id');
            $queryObject->join('itemmaster', 'itemmaster.item_id', '=', 'inquiry_product.item_id');
            $queryObject->whereRaw("`name` LIKE '%" . $search_item['name'] . "%'");
            $selectedItem = $search_item['name'];
        }
        if (!empty($search_item['contact_person'])) {
            $queryObject->whereRaw("`contact_person` LIKE '%" . $search_item['contact_person'] . "%'");
            $selectedContactPer = $search_item['contact_person'];

        }
        if (!empty($search_item['subject'])) {
            $queryObject->whereRaw("`subject` LIKE '%" . $search_item['subject'] . "%'");
            $selectedSubject = $search_item['subject'];

        }
        if (!empty($search_item['email'])) {
            $queryObject->whereRaw("inquiry.`email` LIKE '%" . $search_item['email'] . "%'");
            $selectedEmail = $search_item['email'];
        }
        if (!empty($search_item['phone_no'])) {
            $queryObject->whereRaw("`phone_no` LIKE '%" . $search_item['phone_no'] . "%'");
            $selectedPhone = $search_item['phone_no'];

        }
        $queryObject->get();

        $inquiry = $queryObject->paginate(10);


        return view('Inquiry.index')->with(compact('user', 'inquiry', 'inquiryproductitems', 'search_item', 'item', 'unit','customers', 'selectedCustomer', 'selectedItem', 'selectedContactPer', 'selectedSubject', 'selectedEmail', 'selectedPhone'));
    }

    public function sendTelegram(Request $request, $inquiry_id)
    {

        $userInfo = DB::table('users')->where('id', Auth::user()->id)->first();
        $telegramID = $userInfo->telegram_id;

        Notification::route('telegram', $telegramID)
            ->notify(new TelegramNotification($inquiry_id,'Inquiry'));
        $request->session()->flash('success', 'Message Sent Successfully..');
        return redirect()->route('inquiry.index');
    }

    public function Search(Request $request)
    {
        $search = array();
        $search['contact_person'] = $request->post('contact_person');
        $search['name'] = $request->post('item_id');
        $search['company_name'] = $request->post('customer_id');
        $search['subject'] = $request->post('subject');
        $search['email'] = $request->post('email');
        $search['phone_no'] = $request->post('phone_no');
        $request->session()->put('search_item', $search);
        return redirect()->route('inquiry.index');
    }

    public function Clear(Request $request)
    {
        $request->session()->forget('search_item');
        return redirect()->route('inquiry.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $taxrate = TaxRate::all();
        $customers = Customer::all();
        $item = Item::all();
        $unit=Unit::all();
        $payment = PaymentTerms::all();
        $form = 'Insert';
        return view('Inquiry.create')->with(compact('taxrate', 'item','unit', 'customers', 'payment', 'form'));
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
        $financialID = $financial->financial_year_id;
        $messages = [
            'customer_id.required' => 'Please Select Customer',
            'inquiry_from.required' => 'Please Select Inquiry From',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'customer_id' => 'required',
            'inquiry_from' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $inquiry = new Inquiry();
        $inquiry->customer_id = $request->post('customer_id');
        $inquiry->subject = $request->post('subject');
        $inquiry->financial_year_id = $financialID;
        $inquiry->date = date('Y-m-d ', strtotime($request->post('date')));
        $inquiry->inquiry_from = $request->post('inquiry_from');
        $inquiry->assign_id = Auth::user()->id;
        $inquiry->contact_person = $request->post('contact_person');
        $inquiry->phone_no = $request->post('phone_no');
        $inquiry->email = $request->post('email');
        $inquiry->notes = $request->post('notes');
        $inquiry->save();
        $inquiryID = $inquiry->inquiry_id;


        $assign = new Assign();
        $assign->user_id = Auth::user()->id;
        $assign->inquiry_id = $inquiryID;
        $assign->date = date('Y-m-d ');
        $assign->save();

        foreach ($request->post('grid_items') as $item) {
            $qty = empty($item['qty']) ? 0 : $item['qty'];
            $rate = empty($item['rate']) ? 0 : $item['rate'];
            $unit = empty($item['unit']) ? 0 : $item['unit'];
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

            /** inquiryproduct table save */
            $inquiryproductitems = new InquiryProduct();
            $inquiryproductitems->inquiry_id = $inquiryID;
            $inquiryproductitems->item_id = $item['item_id'];
            $inquiryproductitems->p_description = $item['p_description'];
            $inquiryproductitems->unit = $unit;
            $inquiryproductitems->qty = $qty;
            $inquiryproductitems->rate = $rate;
            $inquiryproductitems->discount_rate = $discountRate;
            $inquiryproductitems->discount_type = 'p';
            $inquiryproductitems->discount_amount = $discountAmount;
            $inquiryproductitems->taxable_value = $taxableValue;
            $inquiryproductitems->taxrate = $taxRate;
            $inquiryproductitems->gst_amount = $gstAmount;
            $inquiryproductitems->cgst_amount = $cgst;
            $inquiryproductitems->igst_amount = $igst;
            $inquiryproductitems->sgst_amount = $sgst;
            $inquiryproductitems->item_total_amount = $TotalAmount;

            $inquiryproductitems->save();
//
//            echo "<pre>";
//            print_r($inquiryproductitems);exit();
        }

        $request->session()->flash('success', 'Inquiry created successfully');
        return redirect()->route('inquiry.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Inquiry $inquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function edit($Id)
    {
        $taxrate = TaxRate::all();
        $inquiry = Inquiry::find($Id);
        $payment = PaymentTerms::all();
        $item = Item::all();
        $unit=Unit::all();
        $customers = Customer::all();
        $inquiryproductArray = InquiryProduct::with('getItemName')->where('inquiry_id', $inquiry->inquiry_id)->get();
        $inquiryproductitems = [];
        foreach ($inquiryproductArray as $Items) {
            array_push($inquiryproductitems, [
                'inquiry_id' => $Items->inquiry_id,
                'inquiry_product_id' => $Items->inquiry_product_id,
                'item_id' => $Items->item_id,
                'name' => $Items->getItemName->name,
                'p_description' => $Items->p_description,
                'unit'=>$Items->unit,
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

        $form = 'Update';
        return view('Inquiry.create')->with(compact('taxrate', 'item', 'inquiry', 'inquiryproductitems', 'customers', 'payment', 'form','unit'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function update($ID, Request $request)
    {

        $messages = [
            'customer_id.required' => 'Please Select Customer',
            'inquiry_from.required' => 'Please Select Inquiry From',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'customer_id' => 'required',
            'inquiry_from' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $inquiry = Inquiry::find($ID);
        $inquiry->subject = $request->post('subject');
        $inquiry->customer_id = $request->post('customer_id');
        $inquiry->date = date('Y-m-d ', strtotime($request->post('date')));
        $inquiry->inquiry_from = $request->post('inquiry_from');
        $inquiry->contact_person = $request->post('contact_person');
        $inquiry->phone_no = $request->post('phone_no');
        $inquiry->email = $request->post('email');
        $inquiry->notes = $request->post('notes');
        $inquiry->save();

        $inquiryID = $inquiry->inquiry_id;
        InquiryProduct::where('inquiry_id', $inquiry->inquiry_id)->delete();
        foreach ($request->post('grid_items') as $item) {
            $qty = empty($item['qty']) ? 0 : $item['qty'];
            $rate = empty($item['rate']) ? 0 : $item['rate'];

            $discountRate = empty($item['discount_rate']) ? 0 : $item['discount_rate'];
            $taxRate = empty($item['taxrate']) ? 0 : $item['taxrate'];
            $unit = empty($item['unit']) ? 0 : $item['unit'];
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

            /** inquiryproduct table save */
            $inquiryproductitems = new InquiryProduct();
            $inquiryproductitems->inquiry_id = $inquiryID;
            $inquiryproductitems->item_id = $item['item_id'];
            $inquiryproductitems->p_description = $item['p_description'];
            $inquiryproductitems->unit = $unit;
            $inquiryproductitems->qty = $qty;
            $inquiryproductitems->rate = $rate;
            $inquiryproductitems->discount_rate = $discountRate;
            $inquiryproductitems->discount_type = 'p';
            $inquiryproductitems->discount_amount = $discountAmount;
            $inquiryproductitems->taxable_value = $taxableValue;
            $inquiryproductitems->taxrate = $taxRate;
            $inquiryproductitems->gst_amount = $gstAmount;
            $inquiryproductitems->cgst_amount = $cgst;
            $inquiryproductitems->igst_amount = $igst;
            $inquiryproductitems->sgst_amount = $sgst;
            $inquiryproductitems->item_total_amount = $TotalAmount;
            $inquiryproductitems->save();

        }

        $request->session()->flash('warning', 'Inquiry updated successfully');


        return redirect()->route('inquiry.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inquiry $inquiry, Request $request)
    {
        $status = $message = '';
        if (Inquiry::destroy($inquiry->inquiry_id)) {
            $status = 'error';
            $message = 'Inquiry deleted successfully.';
        } else {

            $status = 'info';
            $message = 'Inquiry failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('inquiry.index');
    }


    public function save(Request $request)
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
        $followup->inquiry_id = $request->input('inquiry_id');
        $followup->created_id = Auth::user()->id;
        $followup->remark = $request->input('remark');
        $followup->next_followup_date = date('Y-m-d ', strtotime($request->post('next_followup_date')));

        $followup->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Follow Up successfully.', 'followup' => ['remark' => $followup->remark, 'next_followup_date' => $followup->next_followup_date, 'inquiry_id' => $followup->inquiry_id]]);
        } else {
            $request->session()->flash('success', 'Follow Up successfully');
            return redirect()->route('inquiry.index');
        }
    }

    public function getCompositionItems(Request $request)
    {


        $inquiry_ID = $request->input('inquiry_id');

        $inquiry = DB::table('inquiry')
            ->join('customer', 'customer.customer_id', '=', 'inquiry.customer_id')
            ->select(['customer.company_name', 'inquiry.inquiry_id', 'inquiry.customer_id', 'inquiry.contact_person'])
            ->where('inquiry.inquiry_id', $inquiry_ID)
            ->first();


        return response()->json(['inquiry' => $inquiry->inquiry_id, 'customer_name' => $inquiry->company_name, 'contact_person' => $inquiry->contact_person], 200);


    }

    public function timeline($inquiry_id)
    {

        $followup = DB::table('followup')
            ->join('users', 'users.id', '=', 'followup.created_id')
            ->select(['users.name', 'followup.remark', 'followup.created_at'])
            ->where('followup.inquiry_id', $inquiry_id)
            ->get();


        return view('Inquiry.followup')->with(compact('followup', 'inquiry_id'));
    }

    public function getInquiryTimeline(Request $request)
    {


        $InquiryID = $request->input('inquiry_id');
        $followup = DB::table('followup')
            ->join('users', 'users.id', '=', 'followup.created_id')
            ->select(['users.name', 'followup.remark', DB::raw("date_format(followup.created_at, '%d-%m-%Y %r')as created_at ")])
            ->where('followup.inquiry_id', $InquiryID)
            ->get()->toArray();


        return response()->json([$followup], 200);
    }

    public function assign(Request $request)
    {

        $messages = [
            'user_id.required' => 'Please Select User',
        ];
        $rules = [
            'user_id' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if (!$request->ajax()) {
            $validator->validate();
        } else {
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            }
        }
        $assign = new Assign();
        $assign->user_id = $request->input('user_id');
        $assign->inquiry_id = $request->input('inquiry_id');
        $assign->date = date('Y-m-d ');
        $assign->save();
        Inquiry::where('inquiry_id', $request->input('inquiry_id'))->update(['assign_id' => $request->post('user_id')]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Assign saved successfully.', 'assign' => ['user_id' => $assign->user_id, 'inquiry_id' => $assign->inquiry_id, 'date' => $assign->date]]);
        } else {
            $request->session()->flash('success', 'Assign saved successfully');
            return redirect()->route('inquiry.index');
        }
    }

    public function getUser(Request $request)
    {
        $inquiry_ID = $request->input('inquiry_id');

        $inquiry = DB::table('inquiry')
            ->join('users', 'users.id', '=', 'inquiry.assign_id')
            ->select(['users.name'])
            ->where('inquiry.inquiry_id', $inquiry_ID)
            ->first();


        return response()->json(['last_user' => $inquiry->name], 200);

    }

    public function rating(Request $request)
    {

        $inquiryID = $request->input('inquiry_id');
        Inquiry::where('inquiry_id', $inquiryID)->update(['ratedIndex' => $request->post('ratedIndex')+1]);


        if ($request->ajax()) {
            return response()->json(['success' => 'Rating successfully.', 'ratedIndex' => ['ratedIndex' => $request->post('ratedIndex')+1], 'inquiry_id' => ['inquiry_id' => $inquiryID]]);
        } else {

            return redirect()->route('inquiry.index');
        }
    }

    public function getInquiry(Request $request)
    {
        $inquiry = Inquiry::with('customer')->with('createdBy')->where('inquiry_id', $request->input('inquiryID'))->first();
        $inquiryProducts = InquiryProduct::with('getItemName')->where('inquiry_id', $request->input('inquiryID'))->get();
        $html = '


<table class="table table-bordered table-sm">
  <tr>
    <th>Date</th>
    <td>' . date('d-m-Y', strtotime($inquiry->date)) . '</td>
  </tr>
  <tr>
    <th>Customer</th>
    <td>' . $inquiry->customer->company_name . '</td>
  </tr>
  <tr>
    <th>Contact Person </th>
    <td> ' . $inquiry->contact_person . '</td>
  </tr>
   <tr>
    <th>Contact Mobile </th>
    <td> ' . $inquiry->phone_no . '</td>
  </tr>
  <tr>
    <th>Contact Email  </th>
    <td> ' . $inquiry->email . '</td>
  </tr>
  <tr>
    <th>Subject  </th>
    <td> ' . $inquiry->subject . '</td>
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

                                            <th class="text-center">Discount (' . ($inquiryProducts[0]->discount_type) . ')</th>
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
        foreach ($inquiryProducts as $inquiryProduct) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $counter . '</td>';
            $html .= '<td>' . (!empty($inquiryProduct->getItemName->name) ? $inquiryProduct->getItemName->name : '') . '</td>';
            $html .= '<td>' . $inquiryProduct->p_description . '</td>';
            $html .= '<td class="text-center">' . $inquiryProduct->qty . '</td>';
            $html .= '<td class="text-center">' . $inquiryProduct->rate . '</td>';
            $html .= '<td class="text-center">' . $inquiryProduct->discount_rate . '</td>';

            $html .= '<td class="text-center">' . number_format($inquiryProduct->taxable_value, 2) . '</td>';
            $html .= '<td class="text-center">' . $inquiryProduct->taxrate . '</td>';
            $html .= '<td class="text-center">' . number_format($inquiryProduct->cgst_amount, 2) . '</td>';
            $html .= '<td class="text-center">' . number_format($inquiryProduct->sgst_amount, 2) . '</td>';
            $html .= '<td class="text-center">' . number_format($inquiryProduct->igst_amount, 2) . '</td>';
            $html .= '<td class="text-center">' . number_format($inquiryProduct->item_total_amount, 2) . '</td>';

            $html .= '</tr>';
            $counter++;
        }
        $html .= '</tbody></table></div>

                                            <div class="form-group row">
                                                <label class="col-10 col-form-label col-form-label-sm">Notes  : ' . $inquiry->notes . '</label>
                                            </div>
                                            ';

        return response($html);
    }

    public function InquiryClose($ID)
    {
        Inquiry::where('inquiry_id', $ID)->update(['inquiry_status' => 'Inquiry Close']);

        return redirect()->route('inquiry.index');

    }
    public function inquiryActive($ID)
    {
        Inquiry::where('inquiry_id', $ID)->update(['inquiry_status' => 'Pending']);

        return redirect()->route('inquiry.index');

    }



    public function SendCustomMsg(Request $request, $inquiry_id)
    {

        $userInfo = DB::table('users')->where('id', Auth::user()->id)->first();
        $telegramID = $userInfo->telegram_id;

        Notification::route('telegram', $telegramID)
            ->notify(new TelegramNotification($inquiry_id,'Inquiry'));
        $request->session()->flash('success', 'Message Sent Successfully..');
        return redirect()->route('inquiry.index');
    }

}




