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

class ItemWiseSPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = Item::all();
        return view('report.itemwise-sp.create')->with(compact( 'item'));
    }



    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'date_range' => 'required',
        ]);
        $item = Item::all();
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
    $itemSummaryQuery1 = DB::table('invoice_items')
        ->join('invoice', 'invoice.invoice_id', '=', 'invoice_items.invoice_id')
        ->join('itemmaster', 'itemmaster.item_id', '=', 'invoice_items.item_id')
        ->join('customer', 'customer.customer_id', '=', 'invoice.customer_id')
        ->select(
            'itemmaster.name as item_name','invoice.invoice_no', 'invoice_items.qty','invoice.financial_year_id','invoice.invoice_date','customer.company_name');

        $itemSummaryQuery = $itemSummaryQuery1->whereBetween('invoice.invoice_date', [$from, $to])
            ->where('invoice.financial_year_id', $financialID);
        $itemSummaryQuery = $itemSummaryQuery1->orderBy('invoice.invoice_date', 'ASC');
        if ($selectedItem ==  !in_array('A', $salesID)){
            $itemSummaryQuery = $itemSummaryQuery1->whereIn('itemmaster.item_id', $salesID);
        }
        $itemSummaryQuery = $itemSummaryQuery1->get();

    }

    elseif ($selectedItem == 'purchase_wise') {
    $itemSummaryQuery1 = DB::table('purchase_product')
        ->join('purchase', 'purchase.purchase_id', '=', 'purchase_product.purchase_id')
        ->join('itemmaster', 'itemmaster.item_id', '=', 'purchase_product.item_id')
        ->join('customer', 'customer.customer_id', '=', 'purchase.customer_id')
        ->select(
            'itemmaster.name','purchase.bill_no', 'purchase_product.qty as qtys','purchase.financial_year_id','purchase.bill_date','customer.company_name as cn');

        $itemSummaryQuery = $itemSummaryQuery1->whereBetween('purchase.bill_date', [$from, $to])
            ->where('purchase.financial_year_id', $financialID);
        $itemSummaryQuery = $itemSummaryQuery1->orderBy('purchase.bill_date', 'ASC');
        if ($selectedItem ==  !in_array('A', $purchaseID)){
            $itemSummaryQuery = $itemSummaryQuery1->whereIn('itemmaster.item_id', $purchaseID);
        }
        $itemSummaryQuery = $itemSummaryQuery1->get();
    }




        return view('report.itemwise-sp.create')->with(compact('item','itemSummaryQuery','purchaseID','salesID','selectedItem'))->with(with(['date_range' => $request->post('date_range')]));


    }





}
