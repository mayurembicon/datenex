<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\Invoice;
use App\Item;
use App\Notifications\TelegramNotification;
use App\Quotation;
use App\Sales;
use App\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        $inquiryIndiaMart = [
            Inquiry::where('assign_id', Auth::user()->id)->where('inquiry_from', 'IndiaMART')->count(),
        ];
        $inquiryTradeIndia = [
            Inquiry::where('assign_id', Auth::user()->id)->where('inquiry_from', 'TradeIndia')->count(),
        ];
        $inquiry = [

            Inquiry::where('assign_id', Auth::user()->id)->count(),
        ];
        $quotation = [

            Quotation::where('created_id', Auth::user()->id)->count(),
        ];
        $invoice = [

            Sales::where('created_id', Auth::user()->id)->count(),
        ];
        $date = date('Y-m-d');
        $query = Inquiry::with('customer')->with('quotation')->with('createdBy');

        /*  Start Inquiry Followup*/

        $InquirytodayFollowUp = DB::table('inquiry')
            ->select('customer.company_name', 'followup.next_followup_date', 'inquiry.inquiry_id')
            ->join('customer', 'customer.customer_id', '=', 'inquiry.customer_id')
            ->join('followup', 'followup.inquiry_id', '=', 'inquiry.inquiry_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.inquiry_id = inquiry.inquiry_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date', $date)
            ->groupBy('inquiry.inquiry_id', 'followup.followup_id')
            ->get();


        $InquirymissedFollowUp =  DB::table('inquiry')
            ->select('customer.company_name', 'followup.next_followup_date', 'inquiry.inquiry_id')
            ->join('customer', 'customer.customer_id', '=', 'inquiry.customer_id')
            ->join('followup', 'followup.inquiry_id', '=', 'inquiry.inquiry_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.inquiry_id = inquiry.inquiry_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date','<', $date)
            ->groupBy('inquiry.inquiry_id', 'followup.followup_id')
            ->get();
        /*  End Inquiry Followup*/

        /*  Start Quotation Followup*/
        $QuotodayFollowUp = DB::table('quotation')
            ->select('customer.company_name', 'followup.next_followup_date', 'quotation.quotation_id')
            ->join('customer', 'customer.customer_id', '=', 'quotation.customer_id')
            ->join('followup', 'followup.quotation_id', '=', 'quotation.quotation_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.quotation_id = quotation.quotation_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date', $date)
            ->groupBy('quotation.quotation_id', 'followup.followup_id')
            ->get();
        $QuomissedFollowUp = DB::table('quotation')
            ->select('customer.company_name', 'followup.next_followup_date', 'quotation.quotation_id')
            ->join('customer', 'customer.customer_id', '=', 'quotation.customer_id')
            ->join('followup', 'followup.quotation_id', '=', 'quotation.quotation_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.quotation_id = quotation.quotation_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date','<', $date)
            ->groupBy('quotation.quotation_id', 'followup.followup_id')
            ->get();
        /*  End Quotation Followup*/


        /*  Start Pi Followup*/
        $PitodayFollowUp = DB::table('pi')
            ->select('customer.company_name', 'followup.next_followup_date', 'pi.pi_id')
            ->join('customer', 'customer.customer_id', '=', 'pi.customer_id')
            ->join('followup', 'followup.pi_id', '=', 'pi.pi_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.pi_id = pi.pi_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date', $date)
            ->groupBy('pi.pi_id', 'followup.followup_id')
            ->get();
        $PimissedFollowUp = DB::table('pi')
            ->select('customer.company_name', 'followup.next_followup_date', 'pi.pi_id')
            ->join('customer', 'customer.customer_id', '=', 'pi.customer_id')
            ->join('followup', 'followup.pi_id', '=', 'pi.pi_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.pi_id = pi.pi_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date','<', $date)
            ->groupBy('pi.pi_id', 'followup.followup_id')
            ->get();
        /*  End Pi Followup*/


        /*  Start customer_inquiry Followup*/
        $CitodayFollowUp = DB::table('customer_inquiry')
            ->select('customer_inquiry.company_name', 'followup.next_followup_date', 'customer_inquiry.customer_inquiry_id')
            ->join('followup', 'followup.c_i_id', '=', 'customer_inquiry.customer_inquiry_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.c_i_id = customer_inquiry.customer_inquiry_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date', $date)
            ->groupBy('customer_inquiry.customer_inquiry_id', 'followup.followup_id')
            ->get();
        $CimissedFollowUp = DB::table('customer_inquiry')
            ->select('customer_inquiry.company_name', 'followup.next_followup_date', 'customer_inquiry.customer_inquiry_id')
            ->join('followup', 'followup.c_i_id', '=', 'customer_inquiry.customer_inquiry_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.c_i_id = customer_inquiry.customer_inquiry_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date','<', $date)
            ->groupBy('customer_inquiry.customer_inquiry_id', 'followup.followup_id')
            ->get();
        /*  End customer_inquiry Followup*/


        /*  Start online_inquiry Followup*/
        $OitodayFollowUp = DB::table('online_inquiry')
            ->select('online_inquiry.sender_company', 'followup.next_followup_date', 'online_inquiry.o_id')
            ->join('followup', 'followup.o_id', '=', 'online_inquiry.o_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.o_id = online_inquiry.o_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date', $date)
            ->groupBy('online_inquiry.o_id', 'followup.followup_id')
            ->get();
        $OimissedFollowUp = DB::table('online_inquiry')
            ->select('online_inquiry.sender_company', 'followup.next_followup_date', 'online_inquiry.o_id')
            ->join('followup', 'followup.o_id', '=', 'online_inquiry.o_id')
            ->whereRaw('followup.followup_id=(select f.followup_id
                                                           from followup as f
                                                           where f.o_id = online_inquiry.o_id
                                                             and f.next_followup_date is not null
                                                           order by f.followup_id desc
                                                           limit 1)')
            ->where('followup.next_followup_date','<', $date)
            ->groupBy('online_inquiry.o_id', 'followup.followup_id')
            ->get();
        /*  End online_inquiry Followup*/


//        DB::enableQueryLog();

        $pendingInquiryQuery
            = DB::table('inquiry')
            ->select('customer.company_name', 'inquiry.inquiry_id')
            ->join('customer', 'inquiry.customer_id', 'customer.customer_id')
            ->whereNotIn('inquiry_id', DB::table('quotation')->select('inquiry_id'))
            ->orderBy('inquiry.inquiry_id', 'ASC')
            ->get();

//            dd(DB::getQueryLog());exit();

        $starItem = Item::where('ratedIndex', '>', '2')->limit(10)->get();


        return view('home')->with(compact('inquiry', 'quotation', 'invoice', 'starItem', 'pendingInquiryQuery', 'InquirytodayFollowUp',
            'InquirymissedFollowUp',
            'inquiryIndiaMart',
            'inquiryTradeIndia',
            'QuotodayFollowUp',
            'QuomissedFollowUp',
            'PitodayFollowUp',
            'PimissedFollowUp',
            'CitodayFollowUp',
            'CimissedFollowUp',
            'OitodayFollowUp',
            'OimissedFollowUp'

        ));
    }

    public function weekWise()
    {

        $date = \Carbon\Carbon::today()->subDays(7);
        $queryObject = DB::table('itemmaster')
            ->join('invoice_items', 'invoice_items.item_id', '=', 'itemmaster.item_id')
            ->join('invoice', 'invoice.invoice_id', '=', 'invoice_items.invoice_id')
            ->select(['itemmaster.name', 'invoice.invoice_date',
                DB::Raw("count(invoice_items.invoice_id) as totalSales")])
            ->where('invoice.invoice_date', '>=', $date)

//            ->whereRaw('DATE(invoice_items.created_at) = DATE_SUB(CURDATE(), INTERVAL 182 DAY)')

            ->groupBy('itemmaster.item_id')
            ->orderBy('totalSales', 'desc')
            ->limit(10)
            ->get()->toArray();


        return response()->json([$queryObject], 200);


    }

    public function monthWise()
    {
        $date = \Carbon\Carbon::today()->subDays(30);

        $monthWise = DB::table('itemmaster')
            ->join('invoice_items', 'invoice_items.item_id', '=', 'itemmaster.item_id')
            ->join('invoice', 'invoice.invoice_id', '=', 'invoice_items.invoice_id')
            ->select(['itemmaster.name', 'invoice.invoice_date',
                DB::Raw("count(invoice_items.invoice_id) as totalSales")])
            ->where('invoice.invoice_date', '>=', $date)

//            ->whereRaw('DATE(invoice_items.created_at) = DATE_SUB(CURDATE(), INTERVAL 182 DAY)')

            ->groupBy('itemmaster.item_id')
            ->orderBy('totalSales', 'desc')
            ->limit(10)
            ->get()->toArray();


        return response()->json([$monthWise], 200);

    }

    public function sixMonth()
    {
        $date = \Carbon\Carbon::today()->subDays(182);


        $sixMonth = DB::table('itemmaster')
            ->join('invoice_items', 'invoice_items.item_id', '=', 'itemmaster.item_id')
            ->join('invoice', 'invoice.invoice_id', '=', 'invoice_items.invoice_id')
            ->select(['itemmaster.name', 'invoice.invoice_date',
                DB::Raw("count(invoice_items.invoice_id) as totalSales")])
            ->where('invoice.invoice_date', '>=', $date)

//            ->whereRaw('DATE(invoice_items.created_at) = DATE_SUB(CURDATE(), INTERVAL 182 DAY)')

            ->groupBy('itemmaster.item_id')
            ->orderBy('totalSales', 'desc')
            ->limit(10)
            ->get()->toArray();


        return response()->json([$sixMonth], 200);
    }

    public function oneYear()
    {
        $date = \Carbon\Carbon::today()->subDays(365);

        $year = DB::table('itemmaster')
            ->join('invoice_items', 'invoice_items.item_id', '=', 'itemmaster.item_id')
            ->join('invoice', 'invoice.invoice_id', '=', 'invoice_items.invoice_id')
            ->select(['itemmaster.name', 'invoice.invoice_date',
                DB::Raw("count(invoice_items.invoice_id) as totalSales")])
            ->where('invoice.invoice_date', '>=', $date)

//            ->whereRaw('DATE(invoice_items.created_at) = DATE_SUB(CURDATE(), INTERVAL 365 DAY)')

            ->groupBy('itemmaster.item_id')
            ->orderBy('totalSales', 'desc')
            ->limit(10)
            ->get()->toArray();


        return response()->json([$year], 200);
    }

}
