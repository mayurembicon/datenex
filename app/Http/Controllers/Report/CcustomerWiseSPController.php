<?php

namespace App\Http\Controllers\Report;

use App\Customer;
use App\FinancialYear;
use App\Http\Controllers\Controller;
use App\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CcustomerWiseSPController extends Controller
{

    public function index()
    {
        $customer=Customer::all();
        return view('report.customerwise-sp.create')->with(compact( 'customer'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'date_range' => 'required',
        ]);
        $customer=Customer::all();
        $dateRange = explode(' - ', $request->post('date_range'));
        $from = Carbon::createFromFormat('d/m/Y', $dateRange[0])->format('Y-m-d');
        $to = Carbon::createFromFormat('d/m/Y', $dateRange[1])->format('Y-m-d');
        $salesID = $request->post('sales_id');
        $purchaseID = $request->post('purchase_id');
        $selectedItem = $request->post('item');
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $financialID = $financial->financial_year_id;
        $itemSummaryQuery='';

        if($selectedItem=='sales_wise') {
            $validatedData = $request->validate([
                'sales_id' => 'required',
            ]);
        }
        elseif($selectedItem=='purchase_wise'){
            $validatedData = $request->validate([
                'purchase_id' => 'required',
            ]);
        }

        if ($selectedItem == 'sales_wise' ) {
            $itemSummaryQuery1 = DB::table('invoice')
                ->join('customer', 'customer.customer_id', '=', 'invoice.customer_id')
                ->select(
                    'invoice.invoice_no','invoice.invoice_date','invoice.financial_year_id','customer.company_name');

            $itemSummaryQuery = $itemSummaryQuery1->where('invoice.financial_year_id', $financialID);
            $itemSummaryQuery = $itemSummaryQuery1->whereBetween('invoice.invoice_date', [$from, $to]);
            $itemSummaryQuery = $itemSummaryQuery1->orderBy('invoice.invoice_date', 'ASC');
            if ($selectedItem ==  !in_array('A', $salesID)){
                $itemSummaryQuery = $itemSummaryQuery1->whereIn('customer.customer_id', $salesID);
            }
            $itemSummaryQuery = $itemSummaryQuery1->get();

        }

        elseif ($selectedItem == 'purchase_wise') {
            $itemSummaryQuery1 = DB::table('purchase')
                ->join('customer', 'customer.customer_id', '=', 'purchase.customer_id')
                ->select(
                    'purchase.bill_no','purchase.bill_date','customer.company_name as cn');
            $itemSummaryQuery = $itemSummaryQuery1->where('purchase.financial_year_id', $financialID);
            $itemSummaryQuery = $itemSummaryQuery1->whereBetween('purchase.bill_date', [$from, $to]);
            $itemSummaryQuery = $itemSummaryQuery1->orderBy('purchase.bill_date', 'ASC');
            if ($selectedItem ==  !in_array('A', $purchaseID)){
                $itemSummaryQuery = $itemSummaryQuery1->whereIn('customer.customer_id', $purchaseID);
            }
            $itemSummaryQuery = $itemSummaryQuery1->get();
        }




        return view('report.customerwise-sp.create')->with(compact('customer','itemSummaryQuery','purchaseID','salesID','selectedItem'))->with(with(['date_range' => $request->post('date_range')]));





    }


}
