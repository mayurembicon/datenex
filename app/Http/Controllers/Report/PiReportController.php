<?php

namespace App\Http\Controllers\Report;

use App\Customer;
use App\FinancialYear;
use App\Item;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PiReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::all();
        $item = Item::all();
        $user = User::all();
        return view('report.pi-report.create')->with(compact('customer', 'item', 'user'));
    }


    public function store(Request $request)
    {
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

        $customerQuery = DB::table('pi')
            ->join('customer', 'customer.customer_id', '=', 'pi.customer_id')
            ->join('users', 'users.id', '=', 'pi.created_id')
            ->join('pi_items', 'pi_items.pi_id', '=', 'pi.pi_id')
            ->join('itemmaster', 'itemmaster.item_id', '=', 'pi_items.item_id')
            ->select('customer.company_name',
                'customer.customer_id',
                'pi.pi_id',
                'pi.pi_no',
                'pi.order_no',
                'pi.pi_date',
                'pi.due_date',
                'pi.financial_year_id',
                'itemmaster.name as item_name',
                'users.name')
//            ->where('pi.financial_year_id',$financialID)
            ->whereBetween('pi.pi_date', [$from, $to]);
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



        return view('report.pi-report.create')->with(compact('item', 'user', 'customer', 'selectedItem', 'customerFinal','customerID','itemID','userID'))->with(with(['date_range' => $request->post('date_range')]));


    }


}
