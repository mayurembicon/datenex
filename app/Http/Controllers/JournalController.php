<?php

namespace App\Http\Controllers;

use App\Cashbook;
use App\Customer;
use App\FinancialYear;
use App\Journal;
use App\Labour;
use App\Ledger;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedCustomer='';

        $search_item = $request->session()->get('search_item');
        $queryObjects = DB::table('journal')
            ->join('customer', 'customer.customer_id', '=', 'journal.customer_id')
            ->select(['journal.journal_id','journal.customer_id','journal.transaction_type','journal.date','journal.grand_total','journal.description','customer.customer_name','customer.company_name'])

            ->where('ref_type', 'PY')
            ->where('type', 'D');
        if (!empty($search_item['transaction_type'])) {
            $queryObjects->whereRaw("`transaction_type` LIKE '%" . $search_item['transaction_type'] . "%'");
        }
        if (!empty($search_item['company_name'])) {
            $queryObjects->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }

        $queryObjects->get();
        $payment=$queryObjects->paginate(10);
        return view('journal.index')->with(compact('search_item','payment','selectedCustomer'));
    }
    public function searchPayment(Request $request)
    {
        $search = array();
        $search['transaction_type'] =$request->post('transaction_type');

        $search['company_name'] =$request->post('company_name');
        $request->session()->put('search_item',$search);
        return redirect()->route('payment.index');
    }
    public function clearPayment(Request $request)
    {
        $request->session()->forget('search_item');
        return redirect()->route('payment.index');
    }
    public function payment()
    {
        $payment = Journal::where('ref_type', 'PY')->where('type', 'D')->get();
        return view('journal.index')->with('payment', $payment)->with('label', 'Payment');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = Customer::all();
        $type = 'C';
        return view('journal.create')->with('customer', $customer)->with('type', $type);

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
            'transaction_type.required' => 'Please Select Transaction Type',
            'date.required' => 'Please Select Date',
            'customer_id.required' => 'Please Select Customer Name',
            'grand_total.required' => 'Please Enter Amount',

        ];
        $rules = [
            'transaction_type' =>'required',
            'date' => 'required',
            'customer_id' =>'required',
            'grand_total' =>'required',

        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $customer_type = $request->post('customer_name');
        if ($customer_type == 'customer') {
            $customer_id = $request->post('customer_id');
        }

        $payment = new Journal();
        $payment->type = 'D';
        $payment->financial_year_id = $financial->financial_year_id;
        $payment->ref_type = 'PY';
        $payment->customer_id = $request->post('customer_id');
        $payment->transaction_type = $request->post('transaction_type');;
        $payment->date = date('Y-m-d', strtotime($request->post('date')));
        $payment->grand_total = $request->post('grand_total');
        $payment->description = $request->post('description');
        $payment->save();


        $request->session()->flash('success', 'Payment created successfully');
        return redirect()->route('payment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Journal $journal
     * @return \Illuminate\Http\Response
     */
    public function show(Journal $journal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Journal $journal
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {

        $payment=Journal::find($ID);
        $customer = Customer::all();
        return view('journal.create')->with(compact('customer', 'payment'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Journal $journal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)
    {
        $financial = FinancialYear::where('is_default', 'Y')->first();

        $messages = [
            'transaction_type.required' => 'Please Select Transaction Type',
            'date.required' => 'Please Select Date',
            'customer_id.required' => 'Please Select Customer Name',
            'grand_total.required' => 'Please Enter Amount',

        ];
        $rules = [
            'transaction_type' => 'required',
            'date' => 'required',
            'customer_id' => 'required',
            'grand_total' => 'required',

        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $customer_type = $request->post('customer_name');
        if ($customer_type == 'customer') {
            $customer_id = $request->post('customer_id');
        }
        $payment=Journal::find($ID);
        $payment->customer_id = $request->post('customer_id');
        $payment->type = 'D';
        $payment->financial_year_id = $financial->financial_year_id;
        $payment->ref_type = 'PY';
        $payment->customer_id = $request->post('customer_id');
        $payment->transaction_type = $request->post('transaction_type');;
        $payment->date = date('Y-m-d', strtotime($request->post('date')));
        $payment->grand_total = $request->post('grand_total');
        $payment->description = $request->post('description');
        $payment->save();
        $request->session()->flash('success', 'Payment updated successfully');
        return redirect()->route('payment.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Journal $journal
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID, Request $request)
    {
        $journal = Journal::find($ID);
        $status = $message = '';
        if (Journal::destroy($journal->journal_id)) {
            $status = 'error';
            $message = 'Receipt  deleted successfully';
        } else {
            $status = 'info';
            $message = 'Receipt failed to delete';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('payment.index');
    }
}
