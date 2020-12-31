<?php

namespace App\Http\Controllers;

use App\Customer;
use App\FinancialYear;
use App\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerTimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::all();

        return view('customer-timeline.create')->with(compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required',
        ]);
       $CustomerID = $request->input('customer_id');
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $financialID = $financial->financial_year_id;
$inquiry=DB::table('inquiry')
    ->join('customer','customer.customer_id','=','inquiry.customer_id')
    ->select('inquiry.date',
        'inquiry.inquiry_from',

        'inquiry.contact_person',
        'inquiry.phone_no',
        'inquiry.email',
        'inquiry.inquiry_id',
        'inquiry.financial_year_id',
        'customer.company_name',
        'customer.customer_name')

    ->where('inquiry.customer_id',$CustomerID)
    ->where('inquiry.financial_year_id', $financialID)
    ->get();
$customer=Customer::all();


            return view('customer-timeline.create')->with(compact('CustomerID','inquiry','customer'));

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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
