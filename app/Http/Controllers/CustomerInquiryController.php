<?php

namespace App\Http\Controllers;

use App\Assign;
use App\CompanyProfile;
use App\Customer;
use App\CustomerInquiry;
use App\CustomerInquiryProduct;
use App\FollowUp;
use App\Inquiry;
use App\InquiryProduct;
use App\Invoice;
use App\Item;
use App\Library\FPDFExtensions;
use App\Notifications\TelegramNotification;
use App\PaymentTerms;
use App\Quotation;
use App\QuotationProductDetail;
use App\Setting;
use App\State;
use App\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class CustomerInquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companyInfo=CompanyProfile::first();
        $setting = Setting::first();
        $selectedCustomer = '';
        $selectedContactPer = '';
        $selectedSubject = '';
        $selectedEmail = '';
        $selectedPhone = '';
        $search_item = $request->session()->get('search_item');
        $customerinquirys = CustomerInquiry::select('*');
        if (!empty($search_item['company_name'])) {
            $customerinquirys->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
        if (!empty($search_item['contact_person'])) {
            $customerinquirys->whereRaw("`contact_person` LIKE '%" . $search_item['contact_person'] . "%'");
            $selectedContactPer = $search_item['contact_person'];

        }
        if (!empty($search_item['subject'])) {
            $customerinquirys->whereRaw("`subject` LIKE '%" . $search_item['subject'] . "%'");
            $selectedSubject = $search_item['subject'];

        }

        if (!empty($search_item['email'])) {
            $customerinquirys->whereRaw("`email` LIKE '%" . $search_item['email'] . "%'");
            $selectedEmail = $search_item['email'];
        }
        if (!empty($search_item['phone_no'])) {
            $customerinquirys->whereRaw("`phone_no` LIKE '%" . $search_item['phone_no'] . "%'");
            $selectedPhone = $search_item['phone_no'];

        }


        $customerinquiry = $customerinquirys->paginate(10);
        return view('customer-inquiry.index')->with(compact('customerinquiry', 'search_item', 'selectedCustomer', 'selectedContactPer', 'selectedSubject', 'selectedEmail', 'selectedPhone', 'setting','companyInfo'));


    }

    public function searchCustomerInquiry(Request $request)
    {
        $search = array();
        $search['company_name'] = $request->post('company_name');
        $search['contact_person'] = $request->post('contact_person');
        $search['subject'] = $request->post('subject');
        $search['email'] = $request->post('email');
        $search['phone_no'] = $request->post('phone_no');
        $request->session()->put('search_item', $search);
        return redirect()->route('customer-inquiry.index');
    }

    public function clearCustomerInquiry(Request $request)
    {
        $request->session()->forget('search_item');
        return redirect()->route('customer-inquiry.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $state = State::all();
        return view('customer-inquiry.create')->with(compact('state'));
    }

    public function createInquiry($ID)
    {
        $onlineInquiryDetail = CustomerInquiry::find($ID);
        /** start Create Customer */
        $companyName = empty($onlineInquiryDetail->company_name) ? $onlineInquiryDetail->company_name : $onlineInquiryDetail->company_name;
        $customer = Customer::where('company_name', 'like', "%$companyName%")->first();

        if (!$customer) {
            $customer = new Customer();
            $customer->company_name = $companyName;
            $customer->contact_person_name = $onlineInquiryDetail->contact_person;
            $customer->email = $onlineInquiryDetail->email;
            $customer->f_phone_no = $onlineInquiryDetail->phone_no;
            $customer->save();
            $customerID = $customer->customer_id;
        } else {
            $customerID = $customer->customer_id;
        }
        /** end Create Customer */
        $inquiry = new Inquiry();
        $inquiry->inquiry_id = 0;
        $inquiry->contact_person = $onlineInquiryDetail->contact_person;
        $inquiry->phone_no = $onlineInquiryDetail->phone_no;
        $inquiry->email = $onlineInquiryDetail->email;
        $inquiry->customer_id = $customerID;
        $inquiry->notes = $onlineInquiryDetail->notes . "\n" . $onlineInquiryDetail->notes;

        $customerInquiryproduct = CustomerInquiryProduct::where('customer_inquiry_id', $onlineInquiryDetail->customer_inquiry_id)->get();


        $inquiryproductitems = [];
        foreach ($customerInquiryproduct as $Items) {
            array_push($inquiryproductitems, [
                'customer_inquiry_id' => $Items->customer_inquiry_id,
                'customer_inquiry_product_id' => $Items->customer_inquiry_product_id,
                'item_name' => $Items->item_name,
                'p_description' => $Items->p_description . ' ' . ',' . ' ' . $Items->item_name,
                'qty' => $Items->qty,
            ]);
        }
        $item = Item::all();
        $payment = PaymentTerms::all();
        $taxrate = TaxRate::all();
        $customers = Customer::all();
        $form = 'Insert';
        return view('Inquiry.create')->with(compact('customers', 'item', 'inquiry', 'inquiryproductitems', 'payment', 'taxrate', 'form'));

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
            'contact_person.required' => 'Please Enter Contact Person',
            'company_name.required' => 'Please Enter Company Name',
            'phone_no.required' => 'Please Enter Phone No',
            'grid_items.*.item_name.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'contact_person' => 'required',
            'phone_no' => 'required',
            'company_name' => 'required',
            'grid_items.*.item_name' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $inquiry = new CustomerInquiry();
        $inquiry->company_name = $request->post('company_name');
        $inquiry->contact_person = $request->post('contact_person');
        $inquiry->phone_no = $request->post('phone_no');
        $inquiry->subject = $request->post('subject');
        $inquiry->email = $request->post('email');
        $inquiry->notes = $request->post('notes');
        $inquiry->save();
        $inquiryID = $inquiry->customer_inquiry_id;


        foreach ($request->post('grid_items') as $item) {
            $qty = empty($item['qty']) ? 0 : $item['qty'];
            /** Stop Tax Calculation */
            /** inquiryproduct table save */
            $inquiryproductitems = new CustomerInquiryProduct();
            $inquiryproductitems->customer_inquiry_id = $inquiryID;
            $inquiryproductitems->item_name = $item['item_name'];
            $inquiryproductitems->p_description = $item['p_description'];
            $inquiryproductitems->qty = $qty;


            $inquiryproductitems->save();


        }

        $request->session()->flash('success', 'Customer Inquiry created successfully');
        return redirect()->route('customer-inquiry.create');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\CustomerInquiry $customerInquiry
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerInquiry $customerInquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\CustomerInquiry $customerInquiry
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {


        $customerinquiry = CustomerInquiry::find($ID);
        $state = State::all();
        $customerInquiryproduct = CustomerInquiryProduct::where('customer_inquiry_id', $customerinquiry->customer_inquiry_id)->get();
        $customerinquiryitems = [];
        foreach ($customerInquiryproduct as $Items) {
            array_push($customerinquiryitems, [
                'customer_inquiry_id' => $Items->customer_inquiry_id,
                'customer_inquiry_product_id' => $Items->customer_inquiry_product_id,
                'item_name' => $Items->item_name,
                'p_description' => $Items->p_description,
                'qty' => $Items->qty,
            ]);
        }
        return view('customer-inquiry.create')->with(compact('customerinquiry', 'customerinquiryitems', 'state'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\CustomerInquiry $customerInquiry
     * @return \Illuminate\Http\Response
     */
    public function update($ID, Request $request)
    {

        $inquiry = CustomerInquiry::find($ID);
        $inquiry->company_name = $request->post('company_name');
        $inquiry->subject = $request->post('subject');
        $inquiry->contact_person = $request->post('contact_person');
        $inquiry->phone_no = $request->post('phone_no');
        $inquiry->email = $request->post('email');
        $inquiry->notes = $request->post('notes');

        $inquiry->save();


        $inquiryID = $inquiry->customer_inquiry_id;
        CustomerInquiryProduct::where('customer_inquiry_id', $inquiry->customer_inquiry_id)->delete();
        foreach ($request->post('grid_items') as $item) {
            $qty = empty($item['qty']) ? 0 : $item['qty'];

            /** Stop Tax Calculation */

            /** inquiryproduct table save */
            $inquiryproductitems = new CustomerInquiryProduct();
            $inquiryproductitems->customer_inquiry_id = $inquiryID;
            $inquiryproductitems->item_name = $item['item_name'];
            $inquiryproductitems->p_description = $item['p_description'];
            $inquiryproductitems->qty = $qty;
            $inquiryproductitems->save();

        }
        $request->session()->flash('success', 'Customer Inquiry Update successfully');
        return redirect()->route('customer-inquiry.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\CustomerInquiry $customerInquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerInquiry $customerInquiry, Request $request)
    {
        $status = $message = '';
        if (CustomerInquiry::destroy($customerInquiry->customer_inquiry_id)) {
            $status = 'error';
            $message = 'Customer Inquiry deleted successfully.';
        } else {

            $status = 'info';
            $message = 'Customer Inquiry failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('customer-inquiry.index');
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
        $followup->c_i_id = $request->input('c_i_id');
        $followup->created_id = Auth::user()->id;
        $followup->remark = $request->input('remark');
        $followup->next_followup_date = date('Y-m-d ', strtotime($request->post('next_followup_date')));

        $followup->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Follow Up successfully.', 'followup' => ['remark' => $followup->remark, 'next_followup_date' => $followup->next_followup_date, 'c_i_id' => $followup->c_i_id]]);
        } else {
            $request->session()->flash('success', 'Follow Up successfully');
            return redirect()->route('customer-inquiry.index');
        }
    }

    public function getCustomerInfomation(Request $request)
    {
        $CustomerInquiryID = $request->input('customer_inquiry_id');
        $customrinquiry = DB::table('customer_inquiry')
            ->select(['customer_inquiry.company_name', 'customer_inquiry.contact_person', 'customer_inquiry.customer_inquiry_id'])
            ->where('customer_inquiry.customer_inquiry_id', $CustomerInquiryID)
            ->first();
        return response()->json([
            'customer_inquiry_id' => $customrinquiry->customer_inquiry_id,
            'company_name' => $customrinquiry->company_name,
            'contact_person' => $customrinquiry->contact_person
        ], 200);
    }

    public function customerDetail(Request $request)
    {
        $CustomerID = $request->input('customerID');
        $inquiry =CustomerInquiry::where('customer_inquiry_id',$CustomerID)->first();
        $inquiryProducts = CustomerInquiryProduct::where('customer_inquiry_id', $CustomerID)->get();
        $html = '

                                            <div class="form-group row">

                                                <div class="col-6">
                                                 <label class="col-5 col-form-label col-form-label-sm">Date</label><span class="font-weight-bold">: ' . date('d-m-Y', strtotime($inquiry->created_at)) . '</span>
                                                </div>
                                                  <div class="col-6">
                                                 <label class="col-5 col-form-label col-form-label-sm">Company Name</label><span class="font-weight-bold">: ' . $inquiry->company_name . '</span>
                                                </div>
                                            </div>

                                             <div class="form-group row">

                                                <div class="col-6">
                                                 <label class="col-5 col-form-label col-form-label-sm">Contact Person</label><span class="font-weight-bold">: ' . $inquiry->contact_person . '</span>
                                                </div>
                                                 <div class="col-6">
                                                <label class="col-5 col-form-label col-form-label-sm">Contact Mobile</label><span class="font-weight-bold">: ' . $inquiry->phone_no . '</span></div>
                                            </div>


                                             <div class="form-group row">

                                                <div class="col-6">
                                                 <label class="col-5 col-form-label col-form-label-sm">Contact Email</label><span class="font-weight-bold">: ' . $inquiry->email . '</span>
                                                </div>

                                            </div>







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

</tr>
</thead>
<tbody>';
        $counter = 1;
        foreach ($inquiryProducts as $inquiryProduct) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $counter . '</td>';
            $html .= '<td>' . $inquiryProduct->item_name . '</td>';
            $html .= '<td>' . $inquiryProduct->p_description . '</td>';
            $html .= '<td class="text-center">' . $inquiryProduct->qty . '</td>';
            $html .= '</tr>';
            $counter++;
        }
        $html .= '</tbody></table></div>

                                            <div class="form-group row">

                                                <div class="col-6">
                                                 <label class="col-5 col-form-label col-form-label-sm">Notes</label><span class="font-weight-bold">: ' . $inquiry->notes . '</span>
                                                </div>
                                                </div>

                                            ';

        return response($html);
    }

    public function getCiTransaction(Request $request)
    {
        $ci = CustomerInquiry::where('customer_inquiry_id', $request->input('ciID'))->first();
        $ciProducts = CustomerInquiryProduct::where('customer_inquiry_id', $request->input('ciID'))->get();
        $html = '


<table class="table table-bordered table-sm">
   <tr>
    <th>Company Name</th>
    <td>' . $ci->company_name . '</td>
  </tr>
  <tr>
    <th>Contact Person</th>
    <td>' . $ci->contact_person . '</td>
  </tr>

  <tr>
    <th>Phone No </th>
    <td>' . $ci->phone_no . '</td>
  </tr>
  <tr>
    <th>Email  </th>
    <td> ' . $ci->email . '</td>
  </tr>
   <tr>
    <th>Subject</th>
    <td> ' . $ci->subject . '</td>
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


</tr>
</thead>
<tbody>';
        $counter = 1;
        foreach ($ciProducts as $ciProduct) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $counter . '</td>';
            $html .= '<td>' . $ciProduct->item_name . '</td>';
            $html .= '<td>' . $ciProduct->p_description . '</td>';
            $html .= '<td class="text-center">' . $ciProduct->qty . '</td>';


            $html .= '</tr>';
            $counter++;
        }
        $html .= '</tbody></table></div>


                                            <div class="form-group row">
                                                <label class="col-10 col-form-label col-form-label-sm">Notes  : ' . $ci->notes . '</label>
                                            </div>
                                            ';

        return response($html);
    }


    public function sendTelegram(Request $request, $urlType)
    {

        $userInfo = DB::table('users')->where('id', Auth::user()->id)->first();
        $telegramID = $userInfo->telegram_id;

        Notification::route('telegram', $telegramID)
            ->notify(new TelegramNotification(null, $urlType));
        $request->session()->flash('success', 'Message Sent Successfully..');
        return redirect()->route('customer-inquiry.index');
    }
    public function sendEmail(Request $request)
    {



        $mail_title = $request->input('mail_title');
        $mail_body = $request->input('mail_body');
        $email = $request->input('email');
        $attachment = $request->input('attachment');
        $type = 'CustomerInquiry';

        $details = [
            'title' => $mail_title,
            'body' => $mail_body,
            'attachment' => $attachment,
            'type'=>$type


        ];
        Mail::to($email)->send(new \App\Mail\MyTestMail($details));
        $request->session()->flash('success', 'Email Sent Successfully');
        return redirect()->route('customer-inquiry.index');
    }
}
