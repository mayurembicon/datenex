<?php

namespace App\Http\Controllers;

use App\Customer;
use App\FinancialYear;
use App\Journal;
use App\Models\Customers;
use App\Models\FinacialMaster;
use App\Models\Invoice;
use App\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedCustomer='';
        $search_item = $request->session()->get('search_receipt');

        $queryObjects = DB::table('journal')

            ->join('customer', 'customer.customer_id', '=', 'journal.customer_id')
            ->select(['journal.journal_id','journal.customer_id','journal.transaction_type','journal.date','journal.grand_total','journal.description','customer.customer_name','customer.company_name'])
            ->where('ref_type', 'RC')
            ->where('type', 'C');
        if (!empty($search_item['transaction_type'])) {
            $queryObjects->whereRaw("`transaction_type` LIKE '%" . $search_item['transaction_type'] . "%'");
        }
        if (!empty($search_item['company_name'])) {
            $queryObjects->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
        $queryObjects->get();
        $receipt=$queryObjects->paginate(10);
        return view('receipt.index')->with(compact('search_item','receipt','selectedCustomer'));

    }
    public function searchReceipt(Request $request)
    {
        $search = array();
        $search['transaction_type'] =$request->post('transaction_type');

        $search['company_name'] =$request->post('company_name');
        $request->session()->put('search_receipt',$search);
        return redirect()->route('receipt.index');
    }
    public function clearReceipt(Request $request)
    {
        $request->session()->forget('search_receipt');
        return redirect()->route('receipt.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        $type = 'C';
        return view('receipt.create')->with(compact('customers', 'type'));

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
            'transaction_type' => 'required',
            'date' => 'required',
            'customer_id' => 'required',
            'grand_total' => 'required',

        ];
        Validator::make($request->all(), $rules, $messages)->validate();

        $customer_type = $request->post('customer_type');
        if ($customer_type == 'customer') {
            $customer_id = $request->post('customer_id');
        }


        $receipt = new Journal();
        $receipt->type = $request->post('type');
        $receipt->financial_year_id = $financial->financial_year_id;
        $receipt->ref_type = 'RC';
        $receipt->customer_id = $request->post('customer_id');
        $receipt->transaction_type = $request->post('transaction_type');
        $receipt->date = date('Y-m-d', strtotime($request->post('date')));
        $receipt->grand_total = $request->post('grand_total');
        $receipt->description = $request->post('description');
        $receipt->save();

        return redirect()->route('receipt.index');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Receipt $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Receipt $receipt
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {
        $customers = Customer::all();
        $receipt = Journal::find($ID);
        return view('receipt.create')->with(compact('customers', 'receipt'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Receipt $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $financial = FinancialYear::where('is_default', 'Y')->first();

        $messages = [
            'type.required' => 'Please Select Transaction Type',
            'date.required' => 'Please Select Date',
            'customer_id.required' => 'Please Select Customer Name',
            'grand_total.required' => 'Please Enter Amount',

        ];
        $rules = [
            'type' => 'required',
            'date' => 'required',
            'customer_id' => 'required',
            'grand_total' => 'required',

        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $customer_type = $request->post('customer_type');
        if ($customer_type == 'customer') {
            $customer_id = $request->post('customer_id');
        }
        $receipt = Journal::find($id);
        $receipt->type = $request->post('type');
        $receipt->financial_year_id = $financial->financial_year_id;
        $receipt->ref_type = 'RC';
        $receipt->customer_id = $request->post('customer_id');
        $receipt->transaction_type = $request->post('transaction_type');
        $receipt->date = date('Y-m-d', strtotime($request->post('date')));
        $receipt->grand_total = $request->post('grand_total');
        $receipt->description = $request->post('description');
        $receipt->save();
        return redirect()->route('receipt.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Receipt $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy($ID,Request $request)
    {
        $receipt = Journal::find($ID);
        $status = $message = '';
        if (Journal::destroy($receipt->journal_id)) {
            $status = 'error';
            $message = 'Receipt  deleted successfully';
        } else {
            $status = 'info';
            $message = 'Receipt failed to delete';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('receipt.index');
    }


}
