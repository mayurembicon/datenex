<?php

namespace App\Http\Controllers\Report;

use App\FinancialYear;
use App\Http\Controllers\Controller;
use App\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = Item::all();
        return view('report.stock.create')->with(compact('item'));

    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'date_range' => 'required',
        ]);
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $financial_id = $financial->financial_year_id;
        $item = Item::all();
        $dateRange = explode(' - ', $request->post('date_range'));
        $from = Carbon::createFromFormat('d/m/Y', $dateRange[0])->format('Y-m-d');
        $to = Carbon::createFromFormat('d/m/Y', $dateRange[1])->format('Y-m-d');
        $itemMasterID = $request->post('item_id');

        $selectedItem = $request->post('item');

        if ($selectedItem == 'itemwise') {
            $validatedData = $request->validate([
                'item_id' => 'required',
            ]);
        }
//        DB::enableQueryLog();
        $itemSummaryQuery1 = DB::table('itemmaster')
            ->select(
                'itemmaster.name as item_name',
                DB::Raw("(select opening_stock from opening_stock where opening_stock.item_id = itemmaster.item_id AND financial_year_id = $financial_id) as opening_stock"),
                DB::Raw("(select current_stock from current_stock where item_id = itemmaster.item_id ) as current_stock"),
                DB::Raw("(select sum(qty) from purchase_product left join purchase p on p.purchase_id = purchase_product.purchase_id where p.financial_year_id = $financial_id AND bill_date < '$from' AND purchase_product.item_id = itemmaster.item_id group by itemmaster.item_id  ) as purchase_opening "),
                DB::Raw("(select sum(qty) from purchase_product left join purchase p on p.purchase_id = purchase_product.purchase_id where p.financial_year_id = $financial_id AND bill_date BETWEEN '$from' AND '$to' AND purchase_product.item_id = itemmaster.item_id group by itemmaster.item_id  ) as current_purchase"),
                DB::Raw("(select sum(qty) from sales_return_items left join sales_return s on s.sales_return_id = sales_return_items.sales_return_id where s.financial_year_id = $financial_id AND sales_return_date < '$from' AND sales_return_items.item_id = itemmaster.item_id group by itemmaster.item_id  ) as salesReturnOpening "),
                DB::Raw("(select sum(qty) from sales_return_items left join sales_return s on s.sales_return_id = sales_return_items.sales_return_id where s.financial_year_id= $financial_id AND sales_return_date BETWEEN '$from' AND '$to' AND itemmaster.item_id = itemmaster.item_id  group by itemmaster.item_id ) as currentSalesReturn"),


                DB::Raw("(select sum(qty) from invoice_items left join invoice s on s.invoice_id = invoice_items.invoice_id where s.financial_year_id = $financial_id AND invoice_date < '$from' AND invoice_items.item_id = itemmaster.item_id group by itemmaster.item_id  ) as sales_opening"),
                DB::Raw("(select sum(qty) from invoice_items left join invoice s on s.invoice_id = invoice_items.invoice_id where s.financial_year_id = $financial_id AND invoice_date BETWEEN '$from' AND '$to' AND itemmaster.item_id = itemmaster.item_id  group by itemmaster.item_id ) as current_sales"),
                DB::Raw("(select sum(qty) from purchase_return_items left join purchase_return s on s.purchase_return_id = purchase_return_items.purchase_return_id where s.financial_year_id = $financial_id AND purchase_return_date < '$from' AND purchase_return_items.item_id = itemmaster.item_id group by itemmaster.item_id ) as purchaseReturnOpening"),
                DB::Raw("(select sum(qty) from purchase_return_items left join purchase_return s on s.purchase_return_id = purchase_return_items.purchase_return_id where s.financial_year_id = $financial_id AND purchase_return_date BETWEEN '$from' AND '$to' AND itemmaster.item_id = itemmaster.item_id group by itemmaster.item_id ) as currentPurchaseReturn"));
        if ($selectedItem == 'itemwise' && !in_array('A', $itemMasterID)) {
            $purchase = $itemSummaryQuery1->whereIn('itemmaster.item_id', $itemMasterID);

        }

        $itemSummaryQuery = $itemSummaryQuery1->get();
//        dd(DB::getQueryLog());
        return view('report.stock.create')->with(compact('itemSummaryQuery', 'itemMasterID', 'selectedItem', 'item'))->with(with(['date_range' => $request->post('date_range')]));

    }


}
