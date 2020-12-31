<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerAddress;
use App\CustomerConatactPerson;
use App\CustomerOpeningBalance;
use App\Financial_Year;
use App\FinancialYear;
use App\OpeningStock;
use App\PaymentTerms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PharIo\Manifest\Requirement;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedCustomer = '';
        $selectedEmail = '';
        $selectedPhone = '';
        $selectedContactPer = '';

        $openingbalance = CustomerOpeningBalance::all();
        $customeraddress = CustomerAddress::all();
        $contactperson = CustomerConatactPerson::all();




        $search_item = $request->session()->get('search_item');

        $customers = Customer::select('*');
        if (!empty($search_item['company_name'])) {
            $customers->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
        if (!empty($search_item['customer_name'])) {
            $customers->whereRaw("`customer_name` LIKE '%" . $search_item['customer_name'] . "%'");
            $selectedContactPer = $search_item['customer_name'];

        }
        if (!empty($search_item['email'])) {
            $customers->whereRaw("`email` LIKE '%" . $search_item['email'] . "%'");
            $selectedEmail = $search_item['email'];
        }
        if (!empty($search_item['f_phone_no'])) {
            $customers->whereRaw("`f_phone_no` LIKE '%" . $search_item['f_phone_no'] . "%'");
            $selectedPhone = $search_item['f_phone_no'];

        }

        $customer = $customers->paginate(10);
        return view('customer.index')->with(compact('openingbalance', 'customer', 'customeraddress', 'contactperson', 'search_item','selectedCustomer','selectedContactPer','selectedEmail','selectedPhone'));

    }

    public function searchCustomer(Request $request)
    {
        $search = array();
        $search['company_name'] = $request->post('company_name');
        $search['customer_name'] = $request->post('customer_name');
        $search['email'] = $request->post('email');
        $search['f_phone_no'] = $request->post('f_phone_no');
        $request->session()->put('search_item', $search);
        return redirect()->route('customer.index');
    }

    public function clearCustomer(Request $request)
    {
        $request->session()->forget('search_item');
        return redirect()->route('customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $payment = PaymentTerms::all();
        return view('customer.create')->with(compact('payment'));
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

            'company_name.required' => 'Please Enter Company Name',
            'customer_name.required' => 'Please Enter Contact Name',
            'f_phone_no.required' => 'Please Enter Phone Number',

        ];
        $rules = [
            'company_name' => 'required|max:150',
            'customer_name' => 'required|max:150',
            'opening_balance_type' => 'required|in:C,D',
            'customer_type' => 'required|in:V,C,B',
            'f_phone_no' => 'required|min:10|numeric',


        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if (!$request->ajax()) {
            $validator->validate();
        } else {
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            }
        }

        $customer = new Customer();
        $customer->payment_terms_id = $request->post('payment_terms_id');
        $customer->customer_type = $request->post('customer_type');
        $customer->customer_name = $request->post('customer_name');
        $customer->company_name = $request->post('company_name');
        $customer->email = $request->post('email');
        $customer->f_phone_no = $request->post('f_phone_no');
        $customer->s_phone_no = $request->post('s_phone_no');
        $customer->website = $request->post('website');
        $customer->contact_person_name = $request->post('contact_person_name');
        $customer->gst_no = $request->post('gst_no');
        $customer->remark = $request->post('remark');
        $customer->place_of_supply = $request->post('place_of_supply');
        $customer->notes = $request->post('notes');
        $customer->save();

        $CustomerID = $customer->customer_id;
        $customerOpeningBalance = new CustomerOpeningBalance();
        $customerOpeningBalance->customer_id = $CustomerID;
        $customerOpeningBalance->financial_year_id = $financial->financial_year_id;
        $customerOpeningBalance->opening_balance = $request->post('opening_balance');
        $customerOpeningBalance->opening_balance_type = $request->post('opening_balance_type');
        $customerOpeningBalance->save();


        $customeraddress = new CustomerAddress();
        $customeraddress->customer_id = $CustomerID;
        $customeraddress->billing_attention = $request->post('billing_attention');
        $customeraddress->country_id = $request->post('country_id');
        $customeraddress->billing_address1 = $request->post('billing_address1');
        $customeraddress->billing_address2 = $request->post('billing_address2');
        $customeraddress->billing_address3 = $request->post('billing_address3');
        $customeraddress->billing_pincode = $request->post('billing_pincode');
        $customeraddress->billing_city = $request->post('billing_city');
        $customeraddress->billing_distinct = $request->post('billing_distinct');
        $customeraddress->state_id = $request->post('state_id');

        $customeraddress->shipping_attention = $request->post('shipping_attention');
        $customeraddress->shipping_country_id = $request->post('shipping_country_id');
        $customeraddress->shipping_address1 = $request->post('shipping_address1');
        $customeraddress->shipping_address2 = $request->post('shipping_address2');
        $customeraddress->shipping_address3 = $request->post('shipping_address3');
        $customeraddress->shipping_city = $request->post('shipping_city');
        $customeraddress->shipping_pincode = $request->post('shipping_pincode');
        $customeraddress->shipping_distinct = $request->post('shipping_distinct');
        $customeraddress->shipping_state_id = $request->post('shipping_state_id');
        $customeraddress->save();


        if (!empty($request->post('grid_items'))) {

            $CustomerID = $customer->customer_id;
            foreach ($request->post('grid_items') as $item) {
                $salutation = empty($item['salutation']) ? 0 : $item['salutation'];
                $contact_person_name = empty($item['contact_person_name']) ? 0 : $item['contact_person_name'];
                $email = empty($item['email']) ? 0 : $item['email'];
                $phone_no = empty($item['phone_no']) ? 0 : $item['phone_no'];
                $designation = empty($item['designation']) ? 0 : $item['designation'];
                $department = empty($item['department']) ? 0 : $item['department'];

                /** Stop Tax Calculation */

                /** invoiceitems table save */
                $contactperson = new CustomerConatactPerson();
                $contactperson->customer_id = $CustomerID;
                $contactperson->salutation = $salutation;
                $contactperson->contact_person_name = $contact_person_name;
                $contactperson->email = $email;
                $contactperson->phone_no = $phone_no;
                $contactperson->designation = $designation;
                $contactperson->department = $department;
                $contactperson->save();
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'Customer created successfully.', 'customer' => ['customer_name' => $customer->company_name, 'customer_id' => $customer->customer_id]]);
        } else {
            $request->session()->flash('success', 'customer created successfully');
            return redirect()->route('customer.index');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {

        $payment = PaymentTerms::all();
        $customer = Customer::find($ID);
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $customerOpeningBalance = CustomerOpeningBalance::where('customer_id', $customer->customer_id)->where('financial_year_id', $financial->financial_year_id)->first();
        $customeraddress = CustomerAddress::where('customer_id',$customer->customer_id)->first();
        $contactpersonArray = CustomerConatactPerson::with('getCustomer')->where('customer_id', $customer->customer_id)->get();
        $contactperson = [];
        foreach ($contactpersonArray as $Items) {
            array_push($contactperson, [
                'customer_id' => $Items->customer_id,
                'contact_person_id' => $Items->contact_person_id,
                'salutation' => $Items->salutation,
                'contact_person_name' => $Items->contact_person_name,
                'email' => $Items->email,
                'phone_no' => $Items->phone_no,
                'designation' => $Items->designation,
                'department' => $Items->department,

            ]);

//            echo "<pre>";
//            print_r($contactperson);exit();
        }
        return view('customer.create')->with(compact('customerOpeningBalance', 'customer', 'customeraddress', 'contactperson', 'payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)

    {
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $messages = [
            'company_name.required' => 'Please Enter Company Name',
            'customer_name.required' => 'Please Enter Contact Name',
            'f_phone_no.required' => 'Please Enter Phone Number',

        ];
        $rules = [
            'company_name' => 'required|max:150',
            'customer_name' => 'required|max:150',
            'opening_balance_type' => 'required|in:C,D',
            'customer_type' => 'required|in:V,C,B',
            ['f_phone_no' => 'required|min:10|max:15|numeric'],


        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        $customer = Customer::find($ID);
        $customer->payment_terms_id = $request->post('payment_terms_id');
        $customer->customer_type = $request->post('customer_type');
        $customer->customer_name = $request->post('customer_name');
        $customer->company_name = $request->post('company_name');
        $customer->email = $request->post('email');
        $customer->f_phone_no = $request->post('f_phone_no');
        $customer->s_phone_no = $request->post('s_phone_no');
        $customer->website = $request->post('website');
        $customer->contact_person_name = $request->post('contact_person_name');
        $customer->gst_no = $request->post('gst_no');
        $customer->remark = $request->post('remark');
        $customer->place_of_supply = $request->post('place_of_supply');
        $customer->notes = $request->post('notes');
        $customer->save();


        CustomerOpeningBalance::where('customer_id', $ID)
            ->update(['opening_balance' => $request->post('opening_balance'), 'opening_balance_type' => $request->post('opening_balance_type')]);

       CustomerAddress::where('customer_id', $customer->customer_id)
           ->update([
               'billing_attention' => $request->post('billing_attention'),
               'country_id' => $request->post('country_id'),
               'billing_address1' => $request->post('billing_address1'),
               'billing_address2' => $request->post('billing_address2'),
               'billing_address3' => $request->post('billing_address3'),
               'billing_city' => $request->post('billing_city'),
               'billing_pincode' => $request->post('billing_pincode'),
               'billing_distinct' => $request->post('billing_distinct'),
               'state_id' => $request->post('state_id'),
               'shipping_attention' => $request->post('shipping_attention'),
               'shipping_country_id' => $request->post('shipping_country_id'),
               'shipping_address1' => $request->post('shipping_address1'),
               'shipping_address2' => $request->post('shipping_address2'),
               'shipping_address3' => $request->post('shipping_address3'),
               'shipping_pincode' => $request->post('shipping_pincode'),
               'shipping_city' => $request->post('shipping_city'),
               'shipping_distinct' => $request->post('shipping_distinct'),
               'shipping_state_id' => $request->post('shipping_state_id'),
           ]);




        if (!empty($request->post('grid_items'))) {

            CustomerConatactPerson::where('customer_id', $customer->customer_id)->delete();
            foreach ($request->post('grid_items') as $item) {
                $salutation = empty($item['salutation']) ?: $item['salutation'];
                $contact_person_name = empty($item['contact_person_name']) ?: $item['contact_person_name'];
                $email = empty($item['email']) ?: $item['email'];
                $phone_no = empty($item['phone_no']) ?: $item['phone_no'];
                $designation = empty($item['designation']) ?: $item['designation'];
                $department = empty($item['department']) ?: $item['department'];


                $contactperson = new CustomerConatactPerson();
                $contactperson->customer_id = $CustomerID;
                $contactperson->salutation = $salutation;
                $contactperson->contact_person_name = $contact_person_name;
                $contactperson->email = $email;
                $contactperson->phone_no = $phone_no;
                $contactperson->designation = $designation;
                $contactperson->department = $department;
                $contactperson->save();
            }
        }


        $request->session()->flash('warning', 'customer updated successfully');
        return redirect()->route('customer.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer, Request $request)
    {
        $status = $message = '';
        if (Customer::destroy($customer->customer_id)) {
            $status = 'error';
            $message = 'customer deleted successfully.';
        } else {
            $status = 'info';
            $message = 'customer failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('customer.index');
    }

    public function getCustomerdata()
    {
        $CustomerID = \request()->input('customer_id');
        $customer = Customer::where('customer_id', $CustomerID)->first();

        return response()->json(['f_phone_no' => $customer->f_phone_no, 'email' => $customer->email, 'contact_person_name' => $customer->customer_name], 200);
    }


}
