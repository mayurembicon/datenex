<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Company;

use App\Inquiry;
use App\InquiryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerList()
    {
        $searchTerm = request()->input('searchTerm');
        $customersQuery = DB::table('customer')->select(DB::raw('customer.customer_id as id'),
            DB::raw('customer.company_name as text'))
            ->whereRaw("(company_name like '%$searchTerm%')")
            ->orderBy('company_name', 'ASC')
            ->limit(10)->get()->toArray();


        array_push($customersQuery, ['id' => 'NEW', 'text' => 'Create new Customer']);
        return response()->json($customersQuery);
    }


    public function stateList()
    {
        $searchTerm = request()->input('searchTerm');
        $countryID = empty(request()->input('country_id')) ? 0 : request()->input('country_id');
        $stateQuery = DB::table('state')->select(DB::raw('state.state_id as id'),
            DB::raw('state.state_name as text'))
            ->whereRaw("(state_name like '%$searchTerm%')")
            ->where('country_id', $countryID)
            ->orderBy('state_name', 'ASC')
            ->limit(10)->get()->toArray();

        return response()->json($stateQuery);
    }

    public function countryList()
    {
        $searchTerm = request()->input('searchTerm');
        $countryQuery = DB::table('country')->select(DB::raw('country.country_id as id'),
            DB::raw('country.country_name as text'))
            ->whereRaw("(country_name like '%$searchTerm%')")
            ->orderBy('country_name', 'ASC')
            ->limit(10)->get()->toArray();

        return response()->json($countryQuery);
    }


    public function placeofsupply()
    {
        $searchTerm = request()->input('searchTerm');
        $countryID = empty(request()->input('country_id')) ? 0 : request()->input('country_id');
        $placeofsupplyQuery = DB::table('state')->select(DB::raw('state.state_id as id'),
            DB::raw('state.state_name as text'))
            ->whereRaw("(state_name like '%$searchTerm%')")
            ->where('country_id', '=','101')
            ->orderBy('state_name', 'ASC')
            ->limit(10)->get()->toArray();

        return response()->json($placeofsupplyQuery);
    }


    public function itemList()
    {

        $searchTerm = request()->input('searchTerm');
        $products = DB::table('itemmaster')->select(DB::raw('itemmaster.item_id as id'),
            DB::raw('itemmaster.name as text'))
            ->whereRaw("(name like '%$searchTerm%')")
            ->orderBy('name', 'ASC')
            ->limit(10)->get()->toArray();


        array_push($products, ['id' => 'NEW', 'text' => 'Create new Item']);
        return response()->json($products);
    }

    public function indexSearchList()
    {
        $searchTerm = request()->input('searchTerm');
        $table_name = request()->input('table_name');
        $field_name = request()->input('field_name');
        $search = DB::table($table_name)->select(DB::raw($field_name . ' as id'),
            DB::raw($field_name . ' as text'))
            ->whereRaw("($field_name like '%$searchTerm%')")
            ->orderBy($field_name, 'ASC')
            ->GroupBy($field_name)
            ->limit(10)->get()->toArray();
        array_unshift($search, ['id' => '0', 'text' => 'Select.. ']);
        return response()->json($search);
    }

    public function getTransction(Request $request)
    {

        if ($request->post('InquiryID')) {
            $inquiry = Inquiry::with('customer')->with('createdBy')->where('inquiry_id', $request->post('InquiryID'))->first();
            $inquiryProducts = InquiryProduct::with('getItemName')->where('inquiry_id', $request->post('InquiryID'))->get();


            $html = '

                                            <div class="form-group row">
                                                <label class="col-2 col-form-label col-form-label-sm">Date</label>
                                                <div class="col-3"><input type="text"  class="form-control font-weight-bold " value="' . date('d-m-Y', strtotime($inquiry->date)) . '"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-2 col-form-label col-form-label-sm">Customer</label>
                                                <div class="col-3"><input type="text"  class="form-control font-weight-bold " value="' . $inquiry->customer->company_name . '"></div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-2 col-form-label col-form-label-sm">Contact Person</label>
                                                <div class="col-5"><input type="text"  class="form-control  font-weight-bold" value="' . $inquiry->contact_person . '"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-2 col-form-label col-form-label-sm">Contact Mobile</label>
                                                <div class="col-3"><input type="text"  class="form-control  font-weight-bold" value="' . $inquiry->phone_no . '"></div>
                                            </div>
                                             <div class="form-group row">
                                                <label class="col-2 col-form-label col-form-label-sm">Contact Email</label>
                                                <div class="col-4"><input type="text"  class="form-control  font-weight-bold" value="' . $inquiry->email . '"></div>
                                            </div>
                                            <div class="k-separator k-separator--border-dashed"></div>
                                            <div class="k-separator k-separator--height-sm"></div>
                                            <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                            <thead>
                                            <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Product Description</th>
                                            <th class="text-center">Qty</th>

</tr>
</thead>
<tbody>';
            $counter = 1;
            foreach ($inquiryProducts as $inquiryProduct) {
                $html .= '<tr>';
                $html .= '<td class="text-center">' . $counter . '</td>';
                $html .= '<td>' . (!empty($inquiryProduct->getItemName->name) ? $inquiryProduct->getItemName->name : '') . '</td>';
                $html .= '<td>' . $inquiryProduct->p_description . '</td>';
                $html .= '<td class="text-center">' . $inquiryProduct->qty . '</td>';
                $html .= '</tr>';
                $counter++;
            }
            $html .= '</tbody></table></div>

                                            <div class="form-group row">
                                                <label class="col-2 col-form-label col-form-label-sm">Notes</label>
                                                <div class="col-7"><textarea   class="form-control  font-weight-bold">' . $inquiry->notes . '</textarea></div>
                                            </div>
                                            ';
        }
        return response($html);


    }
    public function getInvoiceNo()
    {
        $searchTerm = request()->input('searchTerm');
        $customersQuery = DB::table('invoice')->select(DB::raw('invoice.invoice_id as id'),
            DB::raw('invoice.invoice_no as text'))
            ->whereRaw("(invoice_no like '%$searchTerm%')")
            ->orderBy('invoice_no', 'ASC')
            ->limit(10)->get()->toArray();


        array_push($customersQuery, ['id' => 'NEW', 'text' => 'Create new Invoice No']);
        return response()->json($customersQuery);
    }
}

