<?php

namespace App\Http\Controllers\Report;

use App\Customer;
use App\CustomerOpeningBalance;
use App\FinancialYear;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutstandingBalanceController extends Controller
{
    public function index(Request $request)
    {


        $openingbalance = CustomerOpeningBalance::all();
        $customer = Customer::all();
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $financialID = $financial->financial_year_id;
        $Item = $request->post('item');
        $customerID = $request->post('customer_id');

        $customerObject = DB::table('customer')
            ->select(['customer.customer_name','customer.customer_id',
                DB::raw("(SELECT opening_balance FROM customer_opening_balance WHERE financial_year_id = $financialID AND customer_id = customer.customer_id) AS openingBalance"),
                DB::raw("(select opening_balance_type
        from customer_opening_balance
        where financial_year_id = $financialID
          AND customer_id = customer.customer_id) as openingBalanceType"),
                DB::raw("(select ifnull(sum(journal.grand_total), 0)
        from journal
        where  customer_id = customer.customer_id
          AND financial_year_id = $financialID
          and type = 'C')                as totalCredit"),
                DB::raw("(select ifnull(sum(journal.grand_total), 0)
        from journal
        where
          customer_id = customer.customer_id
          AND financial_year_id = $financialID
          and type = 'D')                as totalDebit")
            ]);
        if
        (!empty($customerID)) {
            $customerObject->whereIn('customer.id', $customerID);
        }
        $customers = $customerObject->get();
        return view('report.outstanding-balance.create')->with(compact('customers', 'customer', 'openingbalance', 'Item'))->with(['date_range' => $request->post('date_range')]);
    }

    public function create(Request $request)
    {
        $customer = Customer::all();

        $financial = FinancialYear::where('is_default', 'Y')->first();
        $financialID = $financial->financial_year_id;

        $customerID = $request->post('customer_id');
        $customerObject = DB::table('customer')
            ->select(['customer.customer_name', 'customer.customer_id',
                DB::raw("(SELECT opening_balance FROM customer_opening_balance WHERE financial_year_id = $financialID AND customer.customer_id = customer.customer_id) AS openingBalance"),
                DB::raw("(select opening_balance_type
        from customer_opening_balance
        where financial_year_id = $financialID
          AND customer_id = customer.customer_id) as openingBalanceType"),
                DB::raw("(select ifnull(sum(journal.grand_total), 0)
        from journal
        where  customer_id = customer.customer_id
          AND financial_year_id = $financialID
          and type = 'C')   as totalCredit"),
                DB::raw("(select ifnull(sum(journal.grand_total), 0)
        from journal
        where
          customer_id = customer.customer_id
          AND financial_year_id = $financialID
          and type = 'D') as totalDebit")
            ]);
        if (!empty($customerID)) {
            $customerObject->whereIn('customer.customer_id', $customerID);
        }
        $customers = $customerObject->get();

        return view('report.outstanding-balance.create')->with(compact('customers', 'customer'))->with(['date_range' => $request->post('date_range')]);

    }
}
