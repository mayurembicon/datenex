<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerAddress;
use App\CustomerConatactPerson;
use App\CustomerOpeningBalance;
use App\FollowUp;
use App\Inquiry;
use App\Item;
use App\Notifications\TelegramNotification;
use App\Onlineinquiry;
use App\PaymentTerms;
use App\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class OnlineInquiryController extends Controller
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
        $selectedEmail = '';
        $selectedPhone = '';
        $search_item = $request->session()->get('search_online');

        $onlineInquirys = Onlineinquiry::select('*');
        if (!empty($search_item['inquiry_from'])) {
            $onlineInquirys->whereRaw("`inquiry_from` LIKE '%" . $search_item['inquiry_from'] . "%'");

        }
        if (!empty($search_item['sender_name'])) {
            $onlineInquirys->whereRaw("`sender_name` LIKE '%" . $search_item['sender_name'] . "%'");
            $selectedContactPer = $search_item['sender_name'];

        }
        if (!empty($search_item['sender_company'])) {
            $onlineInquirys->whereRaw("`sender_company` LIKE '%" . $search_item['sender_company'] . "%'");
            $selectedCustomer = $search_item['sender_company'];
        }

        if (!empty($search_item['sender_mobile'])) {
            $onlineInquirys->whereRaw("`sender_mobile` LIKE '%" . $search_item['sender_mobile'] . "%'");
            $selectedPhone = $search_item['sender_mobile'];

        }
        if (!empty($search_item['sender_email'])) {
            $onlineInquirys->whereRaw("`sender_email` LIKE '%" . $search_item['sender_email'] . "%'");
            $selectedEmail = $search_item['sender_email'];

        }
        if (!empty($search_item['product_name'])) {
            $onlineInquirys->whereRaw("`product_name` LIKE '%" . $search_item['product_name'] . "%'");
            $selectedItem = $search_item['product_name'];

        }
        $onlineInquiry = $onlineInquirys->paginate(10);

        return view('online_inquiry.index')->with(compact('onlineInquiry', 'search_item','selectedItem','selectedEmail','selectedPhone','selectedCustomer','selectedContactPer'));
    }

    public function searchInquiry(Request $request)
    {
        $search = array();
        $search['inquiry_from'] = $request->post('inquiry_from');
        $search['product_name'] = $request->post('product_name');
        $search['sender_company'] = $request->post('sender_company');
        $search['sender_name'] = $request->post('sender_name');
        $search['sender_email'] = $request->post('sender_email');
        $search['sender_mobile'] = $request->post('sender_mobile');
        $request->session()->put('search_online', $search);
        return redirect()->route('online-inquiry.index');
    }

    public function clearInquiry(Request $request)
    {
        $request->session()->forget('search_online');
        return redirect()->route('online-inquiry.index');
    }

    public function syncInquiry(Request $request, $inquiryFrom)
    {
        $response = '';
        $currentDate = date('Y-m-d');
        $currentTimeStamp = date('Y-m-d H:i:s');
        $recordCounter = 0;
        $settingInfo = DB::table('setting')->first();
        if(!isset($settingInfo->trade_user_id) OR !isset($settingInfo->trade_profile_id) OR !isset($settingInfo->trade_key) OR !isset($settingInfo->india_mobile_no) OR !isset($settingInfo->india_key)) {
            $request->session()->flash('warning', 'Please Check Setting.');
            return redirect('setting/1/edit');
        }

        if ($inquiryFrom == 'TRADEINDIA') {
            $last_tradeindia_sync = date('Y-m-d', strtotime($settingInfo->last_tradeindia_sync));
//            $response             = file_get_contents( "https://www.tradeindia.com/utils/my_inquiry.html?userid=3810466&profile_id=5164844&key=f8b778dc1fa04e148be1b004a416c6ad&from_date=$last_tradeindia_sync&to_date=$currentDate" );
            $response             = file_get_contents( "https://www.tradeindia.com/utils/my_inquiry.html?userid=".$settingInfo->trade_user_id."&profile_id=".$settingInfo->trade_profile_id."&key=".$settingInfo->trade_key."&from_date=$last_tradeindia_sync&to_date=$currentDate" );
            $responseJSONToArray = json_decode($response);
            foreach ($responseJSONToArray as $item) {
                $inquiryExists = OnlineInquiry::where('inq_uuid', '=', $item->rfi_id)->where('inquiry_from', $inquiryFrom)->first();
                if ($inquiryExists === null) {
                    $onlineInquiry = new OnlineInquiry();
                    $onlineInquiry->inquiry_from = $inquiryFrom;
                    $onlineInquiry->inq_uuid = isset($item->rfi_id) ? $item->rfi_id : 0;
                    $onlineInquiry->inq_date = date('Y-m-d', strtotime($item->generated_date));
                    $onlineInquiry->sender_company = isset($item->sender_co) ? $item->sender_co : '';
                    $onlineInquiry->sender_name = isset($item->sender_name) ? $item->sender_name : '';
                    $onlineInquiry->sender_email = isset($item->sender_email) ? $item->sender_email : '';
//                    $onlineInquiry->sender_other_email  = $item->sender_other_emails;
                    $onlineInquiry->sender_mobile = isset($item->sender_mobile) ? $item->sender_mobile : '';
//                    $onlineInquiry->sender_other_mobile = $item->sender_other_mobiles;
                    $onlineInquiry->sender_city = isset($item->sender_city) ? $item->sender_city : '';
                    $onlineInquiry->sender_state = isset($item->sender_state) ? $item->sender_state : '';
                    $onlineInquiry->sender_country = isset($item->sender_country) ? $item->sender_country : '';
                    $onlineInquiry->subject = isset($item->subject) ? $item->subject : '';
                    $onlineInquiry->notes = isset($item->message) ? $item->message : '';
                    $onlineInquiry->product_name = isset($item->product_name) ? $item->product_name : '';
                    $onlineInquiry->product_qty = isset($item->quantity) ? intval($item->quantity) : 1;
                    $onlineInquiry->financial_id = 1;
                    $onlineInquiry->inq_full_info = json_encode($item);
                    $onlineInquiry->save();
                    $recordCounter++;
                }
            }
            /** Update Last Sync Tradeindia Time */
            DB::table('setting')->where('id', $settingInfo->id)->update(['last_tradeindia_sync' => date('Y-m-d H:i:s')]);
        } elseif ($inquiryFrom == 'INDIAMART') {
            /** Sync time limit in Minute */

            $syncTimeLimit = $settingInfo->indiamart_sync_time_limit;
            $lastSyncTimeStamp = $settingInfo->last_indiamart_sync;

            /** check if time difference more then time limit then sync from Indiamart & Update time in Database */
            $datetime1 = strtotime($lastSyncTimeStamp);
            $datetime2 = strtotime(date('Y-m-d H:i:s'));
            $interval = abs($datetime2 - $datetime1);
            $differenceMinutes = round($interval / 60);
            if ($differenceMinutes < $syncTimeLimit) {
                $request->session()->flash('update-status', 'Please try after ' . ($syncTimeLimit - $differenceMinutes) . ' minute for ' . $inquiryFrom . ' synchronization.');

                return redirect('online-inquiry');
            } else {
//                $response = file_get_contents( "http://mapi.indiamart.com/wservce/enquiry/listing/GLUSR_MOBILE/9510687577/GLUSR_MOBILE_KEY/MTU1ODE3NjQ0Ny4xNzg3IzMwNjk2NTg=/Start_Time/" . str_replace( " ", "%20", $lastSyncTimeStamp ) . "/End_Time/" . str_replace( " ", "%20", $currentTimeStamp ) . "/" );
                $response = file_get_contents( "http://mapi.indiamart.com/wservce/enquiry/listing/GLUSR_MOBILE/".$settingInfo->india_mobile_no."/GLUSR_MOBILE_KEY/".$settingInfo->india_key."/Start_Time/" . str_replace( " ", "%20", $lastSyncTimeStamp ) . "/End_Time/" . str_replace( " ", "%20", $currentTimeStamp ) . "/" );

                $responseJSONToArray = json_decode($response);
                foreach ($responseJSONToArray as $item) {
                    $inquiryExists = OnlineInquiry::where('inq_uuid', '=', $item->QUERY_ID)->where('inquiry_from', $inquiryFrom)->first();
                    if ($inquiryExists === null) {
                        $onlineInquiry = new OnlineInquiry();
                        $onlineInquiry->inquiry_from = $inquiryFrom;
                        $onlineInquiry->inq_uuid = $item->QUERY_ID;
                        $onlineInquiry->inq_date = date('Y-m-d', strtotime($item->DATE_RE));
                        $onlineInquiry->sender_company = $item->GLUSR_USR_COMPANYNAME;
                        $onlineInquiry->sender_name = $item->SENDERNAME;
                        $onlineInquiry->sender_email = $item->SENDEREMAIL;
                        $onlineInquiry->sender_other_email = $item->EMAIL_ALT;
                        $onlineInquiry->sender_mobile = $item->MOB;
                        $onlineInquiry->sender_other_mobile = $item->MOBILE_ALT;
                        $onlineInquiry->sender_city = $item->ENQ_CITY;
                        $onlineInquiry->sender_state = $item->ENQ_STATE;
                        $onlineInquiry->sender_country = $item->COUNTRY_ISO;
                        $onlineInquiry->subject = $item->SUBJECT;
                        $onlineInquiry->notes = $item->ENQ_MESSAGE;
                        $onlineInquiry->product_name = $item->PRODUCT_NAME;
                        $onlineInquiry->product_qty = 1;
                        $onlineInquiry->financial_id = 1;
                        $onlineInquiry->inq_full_info = json_encode($item);
                        $onlineInquiry->save();
                        $recordCounter++;
                    }

                }

                /** Update Last Sync Indiamart Time */
                DB::table('setting')->where('id', $settingInfo->id)->update(['last_indiamart_sync' => date('Y-m-d H:i:s')]);
            }
        }
        if ($recordCounter > 0) {
            $request->session()->flash('create-status', $recordCounter . ' Records synchronize successfully from ' . $inquiryFrom . '.');
        } else {
            $request->session()->flash('delete-status', 'Nothing to synchronize from ' . $inquiryFrom . '.');
        }

        return redirect('online-inquiry');

    }

    public function getOnlineInquiryData()
    {
        include app_path('Http/Controllers/SSP.php');


        /** DB table to use */
        $table = 'online_inquiry_view';

        /** Table's primary key */
        $primaryKey = 'o_id';

        /** Array of database columns which should be read and sent back to DataTables.
         * The `db` parameter represents the column name in the database, while the `dt`
         * parameter represents the DataTables column identifier. In this case simple
         * indexes */
        $columns = array(
            array('db' => 'inquiry_date', 'dt' => 0),
            array('db' => 'inquiry_from', 'dt' => 1),
            array('db' => 'sender_name', 'dt' => 2),
            array('db' => 'sender_company', 'dt' => 3),
            array('db' => 'sender_mobile', 'dt' => 4),
            array('db' => 'sender_email', 'dt' => 5),
            array('db' => 'subject', 'dt' => 6),
            array('db' => 'product_name', 'dt' => 7),
            array('db' => 'action', 'dt' => 8),

        );

        /** SQL server connection information */
        $sql_details = array(
            'user' => env('DB_USERNAME', 'root'),
            'pass' => env('DB_PASSWORD', ''),
            'db' => env('DB_DATABASE', 'embicon'),
            'host' => env('DB_HOST', '127.0.0.1')
        );


        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */


        echo json_encode(
            SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
        );
    }

    public function makeInquiry($onlineInquiryID)
    {
        $onlineInquiryDetail = OnlineInquiry::find($onlineInquiryID);
        /** start Create Customer */
        $companyName = empty($onlineInquiryDetail->sender_company) ? $onlineInquiryDetail->sender_name : $onlineInquiryDetail->sender_company;
        $customer = Customer::where('company_name', 'like', "%$companyName%")->first();

        if (!$customer) {
            $customer = new Customer();
            $customer->company_name = $companyName;
            $customer->contact_person_name = $onlineInquiryDetail->sender_name;
            $customer->email = $onlineInquiryDetail->sender_email;
            $customer->f_phone_no = $onlineInquiryDetail->sender_mobile;
//            $cityInfo                 = DB::table( 'city' )->where( 'city_name', 'like', "%$onlineInquiryDetail->sender_city%" )->first();
//            if ( $cityInfo ) {
//                $customer->billing_city_id = $cityInfo->city_id;
//            }
//            $stateInfo = DB::table( 'state' )->where( 'state_name', 'like', "%$onlineInquiryDetail->sender_state%" )->first();
//            if ( $stateInfo ) {
//                $customer->billing_state_id = $stateInfo->state_id;
//            }
//            $countryInfo = DB::table( 'country' )->where( 'country_name', 'like', "%$onlineInquiryDetail->sender_country%" )->first();
//            if ( $countryInfo ) {
//                $customer->billing_country_id = $countryInfo->country_id;
//            }
//            $customer->shipping_same_as_billing = 'Y';
//            $customer->opening_balance          = 0;
//            $customer->opening_type             = 'C';
//            $customer->is_billed                = 'N';
            $customer->save();
            $customerID = $customer->customer_id;
        } else {
            $customerID = $customer->customer_id;
        }
        /** end Create Customer */
        $inquiry = new Inquiry();
        $inquiry->inquiry_id = 0;
        $inquiry->contact_person = $onlineInquiryDetail->sender_name;
        $inquiry->phone_no = $onlineInquiryDetail->sender_mobile;
        $inquiry->email = $onlineInquiryDetail->sender_email;
        $inquiry->o_id = $onlineInquiryID;
        $inquiry->customer_id = $customerID;
        $inquiry->inquiry_from = $onlineInquiryDetail->inquiry_from;
        $inquiry->notes = $onlineInquiryDetail->subject . "\n" . $onlineInquiryDetail->notes;
        $inquiryproductitems = [];
        array_push($inquiryproductitems, [
            'inquiry_id' => 0,
            'inquiry_product_id' => 0,
            'item_id' => 0,
            'p_description' => $onlineInquiryDetail->product_name,
            'qty' => $onlineInquiryDetail->product_qty,
            'rate' => 0,
            'gst_rate' => 0,
            'cgst_amount' => 0,
            'sgst_amount' => 0,
            'igst_amount' => 0,
            'taxable_value' => 0,
            'discount_rate' => 0,
            'item_total_amount' => 0,
        ]);

//        [
//            'inquiry_id' => 0,
//            'inquiry_product_id' => 0,
//            'item_id' => 0,
//            'p_description' => $onlineInquiryDetail->product_name,
//            'qty' => $onlineInquiryDetail->product_qty,
//            'rate' => 0,
//            'gst_rate' => 0,
//            'cgst_amount' => 0,
//            'sgst_amount' => 0,
//            'igst_amount' => 0,
//            'taxable_value' => 0,
//            'discount_rate' => 0,
//            'item_total_amount' => 0,
//        ];

//        $customers       = Customer::all();
//        $productGroups   = DB::table( 'product_group' )->orderBy( 'group_name', 'ASC' )->get()->toArray();
//        $productMakers   = DB::table( 'product_maker' )->orderBy( 'maker_name', 'ASC' )->get()->toArray();
//        $products        = DB::table( 'product' )->orderBy( 'product_name', 'ASC' )->get()->toArray();
        $item = Item::all();
        $payment = PaymentTerms::all();
        $taxrate = TaxRate::all();
        $customers = Customer::all();
        $form = 'Insert';
        return view('Inquiry.create')->with(compact('customers', 'item', 'inquiry', 'inquiryproductitems', 'payment', 'taxrate', 'form'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Onlineinquiry $onlineinquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Onlineinquiry $onlineinquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Onlineinquiry $onlineinquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(Onlineinquiry $onlineinquiry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Onlineinquiry $onlineinquiry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Onlineinquiry $onlineinquiry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Onlineinquiry $onlineinquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID, Request $request)
    {
        $status = $message = '';
        if (OnlineInquiry::destroy($ID)) {
            $status = 'error';
            $message = 'Online Inquiry deleted successfully.';
        } else {
            $status = 'info';
            $message = 'Online Inquiry failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('online-inquiry.index');
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
        $followup->o_id = $request->input('o_id');
        $followup->created_id = Auth::user()->id;
        $followup->remark = $request->input('remark');
        $followup->next_followup_date = date('Y-m-d ', strtotime($request->post('next_followup_date')));

        $followup->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Follow Up successfully.', 'followup' => ['remark' => $followup->remark, 'next_followup_date' => $followup->next_followup_date, 'o_id' => $followup->o_id]]);
        } else {
            $request->session()->flash('success', 'Follow Up successfully');
            return redirect()->route('customer-inquiry.index');
        }
    }

    public function getCustomerInfo(Request $request)
    {
        $onlinInquiryId = $request->input('o_id');
        $onlinInquiry = DB::table('online_inquiry')
            ->select(['online_inquiry.sender_company','online_inquiry.sender_name','online_inquiry.o_id'])
            ->where('online_inquiry.o_id',$onlinInquiryId)
            ->first();
        return response()->json([
                'o_id' => $onlinInquiry->o_id,
                'sender_company' => $onlinInquiry->sender_company,
                'sender_name' => $onlinInquiry->sender_name]
            , 200);
    }
    public function getOiTransaction(Request $request)
    {
        $oi = Onlineinquiry::where('o_id', $request->input('oiID'))->first();
        $html = '


<table class="table table-bordered table-sm">
   <tr>
    <th>Company Name</th>
    <td>' . $oi->sender_company . '</td>
  </tr>
  <tr>
    <th>Contact Person</th>
    <td>' . $oi->sender_name .  '</td>
  </tr>

  <tr>
    <th>Phone No </th>
    <td>' . $oi->sender_mobile . '</td>
  </tr>
  <tr>
    <th>Email  </th>
    <td> ' . $oi->sender_email . '</td>
  </tr>
   <tr>
    <th>Subject</th>
    <td> ' . $oi->subject . '</td>
  </tr>


</table>

                                            <div class="k-separator k-separator--border-dashed"></div>
                                            <div class="k-separator k-separator--height-sm"></div>
                                            <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                            <thead>
                                            <tr>

                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Qty</th>


</tr>
</thead>
<tbody>';
            $html .= '<tr>';
            $html .= '<td>' .  $oi->product_name  . '</td>';
            $html .= '<td class="text-center">' . $oi->product_qty . '</td>';


            $html .= '</tr>';
        $html .= '</tbody></table></div>


                                            <div class="form-group row">
                                                <label class="col-10 col-form-label col-form-label-sm">Notes  : ' . $oi->notes . '</label>
                                            </div>
                                            ';

        return response($html);
    }
}
