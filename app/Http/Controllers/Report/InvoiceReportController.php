<?php

namespace App\Http\Controllers\Report;

use App\Customer;
use App\FinancialYear;
use App\Item;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceReportController extends Controller
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
        return view('report.invoice-report.create')->with(compact('customer', 'item', 'user'));
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
//        DB::enableQueryLog();
        $itemSummaryQuery='';

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
        if ($selectedItem == 'customer_wise') {
            $itemSummaryQuery1 = DB::table('invoice')
                ->join('customer', 'customer.customer_id', '=', 'invoice.customer_id')
                ->select('customer.company_name','invoice.invoice_no','invoice.invoice_date','invoice.ref_order_no','invoice.financial_year_id');
            if ($selectedItem == !in_array('A', $customerID)) {
                $purchase = $itemSummaryQuery1->whereIn('customer.customer_id', $customerID)
                    ->where('invoice.financial_year_id', $financialID);
            }
            $itemSummaryQuery = $itemSummaryQuery1->get();
        }

        elseif ($selectedItem == 'product_wise') {
            $itemSummaryQuery1 = DB::table('itemmaster')
                ->join('invoice_items', 'invoice_items.item_id', '=', 'itemmaster.item_id')
                ->join('invoice', 'invoice.invoice_id', '=', 'invoice_items.invoice_id')
                ->select([
                    'invoice.invoice_date',
                    'itemmaster.name as item_name',
                    'invoice.invoice_no',
                    'invoice.invoice_date',
                    'invoice.financial_year_id',
                    'invoice_items.qty',
                    DB::Raw("sum(invoice_items.qty) as sumQty"),
                    DB::Raw("sum(invoice_items.item_total_amount) as totalAmount")]);

            $itemSummaryQuery = $itemSummaryQuery1->whereBetween('invoice.invoice_date', [$from, $to])
            ->where('invoice.financial_year_id', $financialID);
            $itemSummaryQuery = $itemSummaryQuery1->groupBy(['itemmaster.item_id']);
            if ($selectedItem == !in_array('A', $itemID)) {
                $itemSummaryQuery = $itemSummaryQuery1->whereIn('itemmaster.item_id', $itemID);
            }
            $itemSummaryQuery = $itemSummaryQuery1->get();
//            dd(DB::getQueryLog());exit();
//            echo "<pre>";
        } elseif ($selectedItem == 'user_wise') {
            $itemSummaryQuery1 = DB::table('invoice')
                ->join('users', 'users.id', '=', 'invoice.created_id')
                ->select(
                    'invoice.invoice_no','invoice.ref_order_no','users.name',
                    'invoice.invoice_date','invoice.financial_year_id');
            $itemSummaryQuery = $itemSummaryQuery1->whereBetween('invoice.invoice_date', [$from, $to])
                ->where('invoice.financial_year_id', $financialID);;
            $itemSummaryQuery = $itemSummaryQuery1->orderBy('invoice.invoice_date', 'ASC');
            if ($selectedItem == !in_array('A', $userID)) {
                $itemSummaryQuery = $itemSummaryQuery1->whereIn('users.id', $userID);
            }
            $itemSummaryQuery = $itemSummaryQuery1->get();

        }



        return view('report.invoice-report.create')->with(compact('item', 'user', 'customer','itemSummaryQuery', 'selectedItem','customerID', 'itemID', 'userID'))->with(with(['date_range' => $request->post('date_range')]));


    }


}
