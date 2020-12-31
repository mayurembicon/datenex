<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $financialInfo = DB::table('financial_year')->where('is_default', 'Y')->first();

        $from = date('d/m/Y', strtotime($financialInfo->start_date));
        $to = date('d/m/Y', strtotime($financialInfo->end_date));

        $customer = Customer::all();
//        Start initializing data
        $journal = Journal::with('customer')->whereBetween('invoice_date', array($financialInfo->start_date, $financialInfo->end_date))->orderBy('invoice_date', 'ASC')->get();
        return view('journal.create')->with(compact('customer',  'from', 'to', 'journal'));
    }

    public function create(Request $request)
    {
        $customer = Customer::all();
        $dateRange = explode(' - ', $request->post('date_range'));
        $from = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[0])));
        $to = date('Y-m-d', strtotime(str_replace('/', '-', $dateRange[1])));
        $transaction = $request->post('transaction');
        $salesTransaction1 = Journal::with('customer')
            ->whereBetween('date', array($from, $to));
        if ($transaction != 'All') {
            $salesTransaction1->where('transaction_type', $transaction);
        }
        $journal = $salesTransaction1->orderBy('invoice_date', 'ASC')
            ->get();
        return view('journal.create')->with(compact('journal', 'customer', 'transaction'));

    }
}
