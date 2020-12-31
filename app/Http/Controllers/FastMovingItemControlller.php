<?php

namespace App\Http\Controllers;

use App\InquiryProduct;
use App\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FastMovingItemControlller extends Controller
{

    public function index()
    {
        return view('report.fast-moving-item.create');
    }



    public function weekWise(){

$date=\Carbon\Carbon::today()->subDays(7);
        $queryObject = DB::table('itemmaster')
            ->join('invoice_items', 'invoice_items.item_id', '=', 'itemmaster.item_id')
            ->select(['itemmaster.name',
                DB::Raw("(select sum(qty) from invoice_items left join itemmaster i on i.item_id = invoice_items.item_id ) as totalSalesQty ")])

            ->where('invoice_items.created_at', '>=', $date)

            ->groupBy('itemmaster.item_id')
            ->orderBy('totalSalesQty','desc')
            ->limit(10)
            ->get()->toArray();


        return response()->json([$queryObject], 200);


    }

    public function monthWise(){
        $date=\Carbon\Carbon::today()->subDays(30);

        $monthWise = DB::table('itemmaster')
            ->join('invoice_items', 'invoice_items.item_id', '=', 'itemmaster.item_id')
            ->select(['itemmaster.name',
                DB::Raw("(select sum(qty) from invoice_items left join itemmaster i on i.item_id = invoice_items.item_id ) as totalSalesQty ")])

            ->where('invoice_items.created_at', '>=', $date)

            ->groupBy('itemmaster.item_id')
            ->orderBy('totalSalesQty','desc')
            ->limit(10)
            ->get()->toArray();


        return response()->json([$monthWise], 200);

    }

    public function sixMonth(){
        $date=\Carbon\Carbon::today()->subDays(182);


        $sixMonth = DB::table('itemmaster')
            ->join('invoice_items', 'invoice_items.item_id', '=', 'itemmaster.item_id')
            ->select(['itemmaster.name',
                DB::Raw("(select sum(qty) from invoice_items left join itemmaster i on i.item_id = invoice_items.item_id ) as totalSalesQty ")])
            ->where('invoice_items.created_at', '>=', $date)

//            ->whereRaw('DATE(invoice_items.created_at) = DATE_SUB(CURDATE(), INTERVAL 182 DAY)')

            ->groupBy('itemmaster.item_id')
            ->orderBy('totalSalesQty','desc')
            ->limit(10)
            ->get()->toArray();


        return response()->json([$sixMonth], 200);
    }
    public function oneYear(){
        $date=\Carbon\Carbon::today()->subDays(365);

        $year = DB::table('itemmaster')
            ->join('invoice_items', 'invoice_items.item_id', '=', 'itemmaster.item_id')
            ->select(['itemmaster.name',
                DB::Raw("(select sum(qty) from invoice_items left join itemmaster i on i.item_id = invoice_items.item_id ) as totalSalesQty ")])
            ->where('invoice_items.created_at', '>=', $date)

//            ->whereRaw('DATE(invoice_items.created_at) = DATE_SUB(CURDATE(), INTERVAL 365 DAY)')

            ->groupBy('itemmaster.item_id')
            ->orderBy('totalSalesQty','desc')
            ->limit(10)
            ->get()->toArray();


        return response()->json([$year], 200);
    }



}
