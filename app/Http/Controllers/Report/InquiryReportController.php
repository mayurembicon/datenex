<?php

namespace App\Http\Controllers\Report;

use App\Customer;
use App\FinancialYear;
use App\Http\Controllers\Controller;
use App\Item;
use App\User;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InquiryReportController extends Controller
{

    public function index()
    {
        $customer = Customer::all();
        $item = Item::all();
        $user = User::all();
        return view('report.inquiry-report.create')->with(compact('customer', 'item', 'user'));
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'date_range' => 'required',
        ]);

        $user = User::all();
        $customer = Customer::all();
        $item = Item::all();
        $dateRange = explode(' - ', $request->post('date_range'));
        $from = Carbon::createFromFormat('d/m/Y', $dateRange[0])->format('Y-m-d');
        $to = Carbon::createFromFormat('d/m/Y', $dateRange[1])->format('Y-m-d');
        $itemID = $request->post('item_id');
        $customerID = $request->post('customer_id');
        $userID = $request->post('user_id');
        $selectedItem = $request->post('item');
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $financialID = $financial->financial_year_id;

        if($selectedItem=='customer_wise') {
            $messages = [
                'customer_id.required' => 'Please Select Company Name',
            ];
            $rules = [
                'customer_id' => 'required',
            ];
            Validator::make($request->all(), $rules, $messages)->validate();
        }
        elseif($selectedItem=='product_wise'){
            $messages = [
                'item_id.required' => 'Please Select Product Name',
            ];
            $rules = [
                'item_id' => 'required',
            ];
            Validator::make($request->all(), $rules, $messages)->validate();
        }
        elseif($selectedItem=='user_wise'){
            $messages = [
                'user_id.required' => 'Please Select User Name',
            ];
            $rules = [
                'user_id' => 'required',
            ];
            Validator::make($request->all(), $rules, $messages)->validate();
        }

            $customerQuery = DB::table('inquiry')
                ->join('customer', 'customer.customer_id', '=', 'inquiry.customer_id')
                ->join('users', 'users.id', '=', 'inquiry.assign_id')
                ->join('inquiry_product', 'inquiry_product.inquiry_id', '=', 'inquiry.inquiry_id')
                ->join('itemmaster', 'itemmaster.item_id', '=', 'inquiry_product.item_id')
                ->select('customer.customer_name',
                    'customer.company_name',
                    'customer.customer_id',
                    'inquiry.date',
                    'inquiry.inquiry_from',
                    'inquiry.phone_no',
                    'inquiry.email',
                    'inquiry.financial_year_id',
                    'itemmaster.name as item_name',
                    'users.name')
                ->where('inquiry.financial_year_id', $financialID)
                ->whereBetween('inquiry.date', [$from, $to]);
        if ($selectedItem == 'customer_wise' && !in_array('A', $customerID)) {
                $purchase = $customerQuery->whereIn('customer.customer_id', $customerID);
            }
        if($selectedItem == 'product_wise' && !in_array('A', $itemID)) {
            $purchase = $customerQuery->whereIn('itemmaster.item_id', $itemID);

        }
        if($selectedItem == 'user_wise' && !in_array('A', $userID)){
            $purchase = $customerQuery->whereIn('users.id', $userID);

        }

            $customerFinal = $customerQuery->get();



        return view('report.inquiry-report.create')->with(compact('item', 'user', 'customer', 'selectedItem', 'customerFinal','customerID','itemID','userID'))->with(with(['date_range' => $request->post('date_range')]));


    }


}
