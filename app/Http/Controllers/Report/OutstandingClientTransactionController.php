<?php

namespace App\Http\Controllers\Report;

use App\Customer;
use App\CustomerOpeningBalance;
use App\FinancialYear;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\FinacialMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutstandingClientTransactionController extends Controller
{
    public function ClientTransaction($id =null)
    {
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $financial_year_id = $financial->financial_year_id;
        $openingBalance = CustomerOpeningBalance::where('customer_id',$id)->where('financial_year_id', $financial_year_id)->first();
        $ledgerInfo=Customer::find($id);
        $journal = DB::table('journal')
            ->select(
                DB::raw(" if(ref_type='PR','Purchase Return',if(ref_type='SR','Sales Return',if(ref_type='SL','Sales',if(ref_type='PU','Purchase',if(ref_type='PY','Payment','Receipt'))))) as ref_type_description")
                , 'journal.date', 'journal.grand_total', 'journal.type','journal.ref_type',
               'journal.transaction_type','journal.transaction_id')
            ->where('customer.customer_id', $id)
            ->join('customer', 'customer.customer_id', '=','journal.customer_id')
            ->get();

        return view('report.customer-deteils.create')->with(compact('openingBalance', 'journal', 'financial', 'ledgerInfo'));
    }
}
