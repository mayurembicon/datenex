<?php

namespace App\Http\Controllers;

use App\City;
use App\CompanyProfile;
use App\Country;
use App\Customer;
use App\CustomerAddress;
use App\FinancialYear;
use App\FollowUp;
use App\Inquiry;
use App\InquiryProduct;
use App\Item;
use App\Library\FPDFExtensions;
use App\Notifications\TelegramNotification;
use App\PaymentTerms;
use App\PurchaseOrder;
use App\PurchaseOrderProduct;
use App\PurchaseReturnItem;
use App\Quotation;
use App\QuotationBillingAddress;
use App\QuotationPayment;
use App\QuotationProductDetail;
use App\QuotationProducts;
use App\QuotationShippingAddress;
use App\Sales;
use App\SalesItems;
use App\Setting;
use App\State;
use App\TaxRate;
use App\TransportDeteils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use function GuzzleHttp\Promise\all;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Mail;


class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $selectedPhone = '';
        $selectedEmail = '';
        $selectedContactPer = '';
        $selectedItem = '';
        $selectedCustomer = '';
        $setting = Setting::first();
        $item = Item::all();
        $customers = Customer::all();
        $quotationcountry = Country::all();
        $quotationcity = City::all();
        $quotationstate = State::all();
        $search_item = $request->session()->get('quotation_item');
        $queryObject = DB::table('quotation')
            ->leftJoin('inquiry', 'inquiry.inquiry_id', '=', 'quotation.inquiry_id')
            ->leftjoin('customer', 'customer.customer_id', '=', 'quotation.customer_id')
            ->select(['quotation.contact_person','quotation.display_quotation_no', 'quotation.phone_no', 'quotation.quotation_status', 'quotation.q_date', 'quotation.email', 'inquiry.date as i_date', 'inquiry.ratedIndex', 'quotation.quotation_id', 'customer.company_name',
                DB::Raw("(select next_followup_date from followup where followup.quotation_id = quotation.quotation_id and next_followup_date is not null ORDER BY followup.followup_id DESC  LIMIT 1) as n_f_d")]);


        if (!empty($search_item['company_name'])) {
            $queryObject->whereRaw("`company_name` LIKE '%" . $search_item['company_name'] . "%'");
            $selectedCustomer = $search_item['company_name'];
        }
        if (!empty($search_item['contact_person'])) {
            $queryObject->whereRaw("quotation.`contact_person` LIKE '%" . $search_item['contact_person'] . "%'");
            $selectedContactPer = $search_item['contact_person'];

        }
        if (!empty($search_item['email'])) {
            $queryObject->whereRaw("quotation.`email` LIKE '%" . $search_item['email'] . "%'");
            $selectedEmail = $search_item['email'];
        }
        if (!empty($search_item['phone_no'])) {
            $queryObject->whereRaw("quotation.`phone_no` LIKE '%" . $search_item['phone_no'] . "%'");
            $selectedPhone = $search_item['phone_no'];

        }
        if (!empty($search_item['name'])) {
            $queryObject->join('quotation_product_detail', 'quotation_product_detail.quotation_id', '=', 'quotation.quotation_id');
            $queryObject->join('itemmaster', 'itemmaster.item_id', '=', 'quotation_product_detail.item_id');
            $queryObject->whereRaw("`name` LIKE '%" . $search_item['name'] . "%'");
            $selectedItem = $search_item['name'];
        }

        $queryObject->get();

        $quotation = $queryObject->paginate(10);

        return view('quotation.index')->with(compact('setting', 'quotationcity', 'quotationstate', 'item', 'quotation', 'search_item', 'customers', 'quotationcountry',
            'selectedCustomer',
            'selectedItem', 'selectedContactPer',
            'selectedEmail', 'selectedPhone'));
    }

    public function searchQuotation(Request $request)
    {
        $search = array();
        $search['company_name'] = $request->post('customer_id');
        $search['contact_person'] = $request->post('contact_person');
        $search['email'] = $request->post('email');
        $search['phone_no'] = $request->post('phone_no');
        $search['name'] = $request->post('item_id');

        $request->session()->put('quotation_item', $search);
        return redirect()->route('quotation.index');
    }

    public function clearQuotation(Request $request)
    {
        $request->session()->forget('quotation_item');
        return redirect()->route('quotation.index');
    }

    public function printQuotation($id, $print_type = '')
    {

        $companyprofile = CompanyProfile::with('getcountry')->with('getstate')->first();
        $quotation = Quotation::with('customer')->with('state')->where('quotation_id', $id)->first();
        $company_state_name = isset($companyprofile->getstate->state_name) ? $companyprofile->getstate->state_name : '';
        $quotation = isset($quotation->state->state_name) ? $quotation->state->state_name : '';

        if ($quotation == $company_state_name) {
            return $this->cgst($id, $print_type);
        } else {
            return $this->igst($id, $print_type);
        }
    }

    public function igst($id, $print_type = '')
    {
        $quotation = Quotation::with('customer')->where('quotation_id', $id)->first();
        $billingaddress = QuotationBillingAddress::with('country')->with('state')->where('quotation_id', $id)->first();

        $companyprofile = CompanyProfile::with('getstate')->with('getcountry')->first();
        $customeraddress = CustomerAddress::first();


        $fpdf = new FPDFExtensions();
        $fpdf->AddPage('P', 'A4');
        $fpdf->AliasNbPages();
        $fpdf->SetMargins(4, 2, 2);
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFont('courier', '', 9);
        $fpdf->SetAutoPageBreak(true);
//        $fpdf->Image('./assets/logo/LogoWatera.jpg', 32, 60, 150, 0);
        $fpdf->SetWidths(array(101, 101));
        $fpdf->SetFont('courier', '', 9);
        $fpdf->Ln();
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', 'B', '12');
        $fpdf->CellFitScale(202, 10, strtoupper(!empty($companyprofile->company_name) ? $companyprofile->company_name : ''), 1, 1, 'C', true);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetFont('courier', '', 10);
        $fpdf->SetWidths(array(202));
        $part1 = strtoupper(!empty($companyprofile->address1) ? $companyprofile->address1 : '') . "\n" . strtoupper(!empty($companyprofile->address2) ? $companyprofile->address2 : '') . strtoupper(!empty($companyprofile->address3) ? $companyprofile->address3 : '') . "\n" . 'COUNTRY:' . strtoupper(!empty($companyprofile->getcountry->country_name) ? $companyprofile->getcountry->country_name : '') . ', STATE:' . strtoupper(!empty($companyprofile->getstate->state_name) ? $companyprofile->getstate->state_name : '') . ', CITY:' . strtoupper(!empty($companyprofile->city) ? $companyprofile->city : '') . ',' . strtoupper(!empty($companyprofile->pincode) ? $companyprofile->pincode : '');
        $fpdf->Row(array($part1), array('C'), '', '', true, 4);


        $fpdf->SetFont('courier', 'B', 12);
        $fpdf->CellFitScale(202, 7, 'QUOTATION', 1, 1, 'C');
        $fpdf->SetFont('courier', 'B', 11);
        $fpdf->CellFitScale(20, 5, 'M/s. :', 'TL', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($quotation->customer->company_name) ? $quotation->customer->company_name : ''), 'TR', 0, 'L');


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(30, 5, 'QTN No ', 'TL', 0, 'L', true);
        $fpdf->CellFitScale(50, 5, ': ' . (!empty($companyprofile->quotation_prefix) ? $companyprofile->quotation_prefix : '') . str_pad($quotation->quotation_id, 3, '0', STR_PAD_LEFT), 'TR', 1, 'L', true);


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address1) ? $customeraddress->billing_address1 : ''), 'R', 0, 'L');


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->CellFitScale(30, 5, 'QTN Date ', 'LB', 0, 'L', true);
        $fpdf->CellFitScale(50, 5, ': ' . date('d/m/Y', strtotime($quotation->q_date)), 'RB', 1, 'L', true);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address2) ? $customeraddress->billing_address2 : ''), 'R', 0, 'L');


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(34, 5, 'Place Of Supply :', 'L', 0, 'L', true);
        $fpdf->CellFitScale(46, 5, (!empty($quotation->state->state_name) ? $quotation->state->state_name : ''), 'R', 1, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address3) ? $customeraddress->billing_address3 : ''), 'R', 0, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(50, 5, " " . '', 'R', 1, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, 'COUNTRY:' . strtoupper(!empty($customeraddress->country->country_name) ? $customeraddress->country->country_name : '') . ', STATE:' . strtoupper(!empty($customeraddress->state->state_name) ? $customeraddress->state->state_name : '') . ', CITY:' . strtoupper(!empty($customeraddress->billing_city) ? $customeraddress->billing_city : '') . strtoupper(!empty($customeraddress->billing_pincode) ? ', ' . $customeraddress->billing_pincode : ''), 'R', 0, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(50, 5, " " . '', 'R', 1, 'L');

        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(16, 5, 'GST IN', 'L', 0, 'L');
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(106, 5, ': ' . (!empty($quotation->customer->gst_no) ? $quotation->customer->gst_no : '') . ' , PAN No : ' . '', 'R', 0, 'L');
        $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(50, 5, '', 'R', 1, 'L', true);
        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(16, 5, 'PH No', 'L', 0, 'L');
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(106, 5, ': ' . (!empty($quotation->phone_no) ? $quotation->phone_no : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(30, 5, '', 'LB', 0, 'L', true);
        $fpdf->CellFitScale(50, 5, '', 'RB', 1, 'L', true);
        $fpdf->SetFont('courier', '', 9);


        $quotaionItem = QuotationProductDetail::with('getItemName')->where('quotation_id', $id)->get();

        $quotaionItemDiscount = QuotationProductDetail::where('quotation_id', $id)->sum('discount_rate');


        $total_rows = 10;
//        $total_rows -=count($salesProduct);
        $fpdf->SetWidths(array(5, 7, 50, 15, 20, 26));
        $fpdf->SetFont('courier', 'B', '');
        $fpdf->Cell(8, 5, 'NO', 'TL', 0, 'C');
        $fpdf->CellFitScale(54, 5, 'Description of Goods / Service', 'TL', 0, 'C');
        $fpdf->Cell(10, 5, 'Qty', 'TL', 0, 'C');
        $fpdf->Cell(20, 5, 'Rate', 'TL', 0, 'C');
        $fpdf->Cell(20, 5, 'Total', 'TL', 0, 'C');
        $fpdf->Cell(20, 5, 'Discount', 'TL', 0, 'C');
        $fpdf->Cell(20, 5, 'Taxable', 'TL', 0, 'C');
        $fpdf->Cell(30, 5, 'IGST', 'TLR', 0, 'C');
        $fpdf->Cell(20, 5, 'Total', 'TLR', 0, 'C');
        $fpdf->Ln();
        $fpdf->Cell(8, 5, '', 'BL', 0, 'C');
        $fpdf->Cell(54, 5, '', 'BL', 0, 'C');
        $fpdf->Cell(10, 5, '', 'BL', 0, 'C');
        $fpdf->Cell(20, 5, 'INR', 'BL', 0, 'C');
        $fpdf->Cell(20, 5, 'INR', 'BL', 0, 'C');
        $fpdf->Cell(20, 5, 'INR', 'BL', 0, 'C');
        $fpdf->Cell(20, 5, 'value INR', 'BL', 0, 'C');
        $fpdf->Cell(10, 5, 'Rate', 'TBLR', 0, 'C');
        $fpdf->Cell(20, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->Cell(20, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', 8);
        $fpdf->SetFont('courier', '', 8);

        $itemTotal = $totalQty = $totalRate = $totalAmount = $totalDiscount = $totalTaxableValue = $totalIgst = $grandTotal = 0;
        $i = 1;
        $itemTotal = 0;
        $quotationItem = QuotationProductDetail::with('getItemName')->where('quotation_id', $id)->get();
        $quotaionItemDiscount = QuotationProductDetail::where('quotation_id', $id)->sum('discount_rate');
        $i = 1;
        $itemTotal = 0;
        $rowTotal = 30;

        foreach ($quotationItem as $value) {
            $total = ($value->rate) * ($value->qty);

            $fpdf->CellFitScale(8, 5, $i++, '1', 0, 'C');
            $fpdf->CellFitScale(54, 5, $value->getItemName->name, '1', 0, 'L');
            $fpdf->CellFitScale(10, 5, $value->qty, 1, 0, 'C');
            $fpdf->CellFitScale(20, 5, $value->rate, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $total, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->discount_amount, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->taxable_value, 1, 0, 'R');
            $fpdf->CellFitScale(10, 5, $value->taxrate, '1', 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->igst_amount, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->item_total_amount, 1, 1, 'R');

            if (!empty($value->p_description)) {
                $fpdf->CellFitScale(202, 5, $value->p_description, 1, 1, 'L');
            }

            $itemTotal = $itemTotal + $value->item_total_amount;
            $totalQty += $value->qty;
            $totalAmount += $total;
            $totalDiscount += $value->discount_amount;
            $totalTaxableValue += $value->taxable_value;
            $totalIgst += $value->igst_amount;
            $grandTotal += $value->item_total_amount;
            $rowTotal -= 2;
        }
        for ($t = 1; $t <= $rowTotal; $t++) {
            $fpdf->CellFitScale(8, 5, '', 'LR', 0, 'C');
            $fpdf->CellFitScale(54, 5, '', 'LR', 0, 'L');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'C');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 1, 'R');

        }

        $grand_total = $grand_qty = 0;
        $totalQty = $totalQty;
        $totalAmount = $totalAmount;
        $totalDiscount = $totalDiscount;
        $totalTaxableValue = $totalTaxableValue;
        $totalIgst = $totalIgst;
        $grandTotal = $grandTotal;

        $fpdf->SetWidths(array(8, 54, 10, 20, 20, 20, 20, 10, 20, 20));
        $fpdf->Row(array('', '  Sub Total', $totalQty, '', number_format($totalAmount, 2), number_format($totalDiscount, 2), number_format($totalTaxableValue, 2), '', number_format($totalIgst, 2), number_format($grandTotal, 2)), array('C', 'L', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R'), false, '', true);
        $fpdf->SetWidths(array(62, 10, 40, 20, 40, 30));
        $fpdf->SetFont('courier', '', 8);

        $taxInfo = DB::table('quotation_product_detail')->selectRaw("max(taxrate) as taxrate")->where('quotation_id', $id)->first();
        $gstTaxRate = $taxInfo->taxrate;
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->CellFitScale(120, 5, 'GSTIN No.:' . (!empty($quotation->customer->gst_no) ? $quotation->customer->gst_no : ''), 1, 0, 'L', true);
        $fpdf->CellFitScale(50, 5, 'Sub Total', 'TLB', 0, 'L', true);
        $fpdf->CellFitScale(32, 5, number_format($itemTotal, 2), 'TRB', 1, 'R', true);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(25, 5, 'Bank Name :', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, (!empty($companyprofile->bank_name) ? $companyprofile->bank_name : ''), 'R', 0, 'L');

        $fpdf->CellFitScale(50, 5, 'P & F ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($quotation->pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(30, 5, 'Bank A/c. No. : ', 'L', 0, 'L');
        $fpdf->CellFitScale(90, 5, (!empty($companyprofile->bank_ac_no) ? $companyprofile->bank_ac_no : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Pf TaxRate ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($quotation->pf_taxrate), 2), 'R', 1, 'R');

        $fpdf->CellFitScale(30, 5, 'RTGS/IFSC Code :', 'L', 0, 'L');
        $fpdf->CellFitScale(90, 5, (!empty($companyprofile->bank_ifsc_code) ? $companyprofile->bank_ifsc_code : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Total With PF ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($quotation->total_with_pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(25, 5, ' ', 'L', 0, 'L');
        $fpdf->CellFitScale(95, 5, ' ', 'R', 0, 'L');
        $fpdf->CellFitScale(50, 5, 'Rounding ', 'L', 0, 'L');
        $fpdf->CellFitScale(32, 5, number_format(($quotation->rounding_amount), 2), 'R', 1, 'R');


        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(15, 5, '', 'LB', 0, 'L');
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(105, 5, '', 'RB', 0, 'L');
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(50, 5, 'Grand Total', 'LBT', 0, 'L', true);
        $fpdf->CellFitScale((32), 5, number_format(round($quotation->grand_total), 2), 'RBT', 1, 'R', true);;
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array(202));
        $fpdf->Row(array('TOTAL AMOUNT IN WORDS INR: ' . strtoupper($this->conver_num_text_money(round($quotation->grand_total)))),
            array('L'), false, '', true);


        /* Footer Payment and condition*/
        if (!empty($quotation->term_condition)) {

            $fpdf->SetFont('courier', 'B', 9);
            $fpdf->CellFitScale(32, 5, "Freight", 'L', 0, 'L');
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->CellFitScale(190, 5, "Terms & Condition", 1, 1, 'L', true);
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetWidths([190]);
            $fpdf->SetFont('courier', 'B', 9);
            $fpdf->Row([$quotation->term_condition], ['L'], '', '', true, 3);

        }

        $quotationpayment = QuotationPayment::with('getPayment')->first();
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(62, 5, "Dispatch Through", 'L', 0, 'L');
        $fpdf->CellFitScale(140, 5, ":   " . (!empty($quotation->dispatch_through) ? $quotation->dispatch_through : ''), 'R', 1, 'L');
        $fpdf->CellFitScale(62, 5, "Delivery Period", 'L', 0, 'L');
        $fpdf->CellFitScale(140, 5, ":   " . (!empty($quotation->delivery_period) ? $quotation->delivery_period : ''), 'R', 1, 'L');
        $fpdf->CellFitScale(62, 5, "Payment", 'L', 0, 'L');
        $fpdf->CellFitScale(140, 5, ":   " . (!empty($quotationpayment->getPayment->payment_terms) ? $quotationpayment->getPayment->payment_terms : ''), 'R', 1, 'L');


        $fpdf->CellFitScale(202, 5, "", 'T', 1);
        /* footer End*/

        if ($print_type == 'SendMail') {
            $file_name = $fpdf->Output('', 'S');
            return $file_name;
        } else if (($print_type == 'Quotation')) {
            $filename = date('ymdhis') . '.pdf';
            $fpdf->Output('telegram/' . $filename, 'F');
            return $filename;
        } else {
            $fpdf->Output();
        }
        exit();


    }

    public function cgst($id, $print_type = '')
    {

        $quotation = Quotation::with('customer')->where('quotation_id', $id)->first();
        $companyprofile = CompanyProfile::with('getstate')->with('getcountry')->first();
        $customeraddress = CustomerAddress::with('country')->with('state')->first();


        $fpdf = new FPDFExtensions();
        $fpdf->AddPage('P', 'A4');
        $fpdf->AliasNbPages();
        $fpdf->SetMargins(1.6, 2, 2);
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFont('courier', '', 9);
        $fpdf->SetAutoPageBreak(true);
//        $fpdf->Image('./assets/logo/LogoWatera.jpg', 32, 60, 150, 0);
        $fpdf->SetWidths(array(101, 101));
        $fpdf->SetFont('courier', '', 9);
        $fpdf->Ln();
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', 'B', '12');
        $fpdf->CellFitScale(207, 10, strtoupper(!empty($companyprofile->company_name) ? $companyprofile->company_name : ''), 1, 1, 'C', true);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetFont('courier', '', 10);
        $fpdf->SetWidths(array(207));
        $part1 = strtoupper(!empty($companyprofile->address1) ? $companyprofile->address1 : '') . "\n" . strtoupper(!empty($companyprofile->address2) ? $companyprofile->address2 : '') . strtoupper(!empty($companyprofile->address3) ? $companyprofile->address3 : '') . "\n" . 'COUNTRY:' . strtoupper(!empty($companyprofile->getcountry->country_name) ? $companyprofile->getcountry->country_name : '') . ', STATE:' . strtoupper(!empty($companyprofile->getstate->state_name) ? $companyprofile->getstate->state_name : '') . ', CITY:' . strtoupper(!empty($companyprofile->city) ? $companyprofile->city : '') . ',' . strtoupper(!empty($companyprofile->pincode) ? $companyprofile->pincode : '');
        $fpdf->Row(array($part1), array('C'), '', '', true, 4);


        $fpdf->SetFont('courier', 'B', 12);
        $fpdf->CellFitScale(207, 7, 'QUOTATION', 1, 1, 'C');
        $fpdf->SetFont('courier', 'B', 11);
        $fpdf->CellFitScale(20, 5, 'M/s. :', 'TL', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($quotation->customer->company_name) ? $quotation->customer->company_name : ''), 'TR', 0, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(30, 5, 'QTN No ', 'TL', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, ': ' . $companyprofile->quotation_prefix . str_pad($quotation->quotation_id, 3, '0', STR_PAD_LEFT), 'TR', 1, 'L', true);


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address1) ? $customeraddress->billing_address1 : ''), 'R', 0, 'L');


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->CellFitScale(30, 5, 'QTN Date', 'LB', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, ': ' . date('d/m/Y', strtotime($quotation->q_date)), 'RB', 1, 'L', true);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address2) ? $customeraddress->billing_address2 : ''), 'R', 0, 'L');


        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(34, 5, 'Place Of Supply :', 'L', 0, 'L', true);
        $fpdf->CellFitScale(51, 5, (!empty($quotation->state->state_name) ? $quotation->state->state_name : ''), 'R', 1, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
        $fpdf->CellFitScale(102, 5, strtoupper(!empty($customeraddress->billing_address3) ? $customeraddress->billing_address3 : ''), 'R', 0, 'L');

        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', '', 10);

        $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, " " . '', 'R', 1, 'L');


        if (!empty($customeraddress->country->country_name)) {

            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetFont('courier', '', 10);
            $fpdf->CellFitScale(20, 5, '', 'L', 0, 'L');
            $fpdf->CellFitScale(102, 5, 'COUNTRY:' . strtoupper(!empty($customeraddress->country->country_name) ? $customeraddress->country->country_name : '') . ', STATE:' . strtoupper(!empty($customeraddress->state->state_name) ? $customeraddress->state->state_name : '') . ', CITY:' . strtoupper(!empty($customeraddress->billing_city) ? $customeraddress->billing_city : '') . strtoupper(!empty($customeraddress->billing_pincode) ? ', ' . $customeraddress->billing_pincode : ''), 'R', 0, 'L');

            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetFont('courier', '', 10);
            $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
            $fpdf->CellFitScale(55, 5, " " . '', 'R', 1, 'L');

        }

        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(20, 5, 'GST IN', 'L', 0, 'L');
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(102, 5, ':' . (!empty($quotation->customer->gst_no) ? $quotation->customer->gst_no : '') . ', PAN No: ' . $companyprofile->pan_no, 'R', 0, 'L');
        $fpdf->CellFitScale(30, 5, '', 'L', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, '', 'R', 1, 'L', true);
        $fpdf->SetFont('courier', 'B', 10);
        $fpdf->CellFitScale(20, 5, 'PH No', 'L', 0, 'L');
        $fpdf->SetFont('courier', '', 10);
        $fpdf->CellFitScale(102, 5, ': ' . (!empty($companyprofile->phono_no) ? $companyprofile->phono_no : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(30, 5, '', 'LB', 0, 'L', true);
        $fpdf->CellFitScale(55, 5, '', 'RB', 1, 'L', true);


        $QuotationItem = QuotationProductDetail::with('getItemName')->where('quotation_id', $id)->get();

        $QuotationItemDiscount = QuotationProductDetail::where('quotation_id', $id)->sum('discount_rate');

        $total_rows = 10;
//        $total_rows -=count($salesProduct);
        $fpdf->SetWidths(array(5, 7, 50, 15, 20, 26));
        $fpdf->SetFont('courier', 'B', '7');
        $fpdf->CellFitScale(8, 5, 'NO', 'TL', 0, 'C');
        $fpdf->CellFitScale(54, 5, 'Description of Goods / Service', 'TL', 0, 'C');
        $fpdf->CellFitScale(10, 5, 'Qty', 'TL', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Rate', 'TL', 0, 'C');
        $fpdf->CellFitScale(20, 5, 'Total', 'TL', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Discount', 'TL', 0, 'C');
        $fpdf->CellFitScale(20, 5, 'Taxable', 'TL', 0, 'C');
        $fpdf->CellFitScale(25, 5, 'SGST', 'TLR', 0, 'C');
        $fpdf->CellFitScale(25, 5, 'CGST', 'TLR', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Total', 'TLR', 0, 'C');
        $fpdf->Ln();
        $fpdf->CellFitScale(8, 5, '', 'BL', 0, 'C');
        $fpdf->CellFitScale(54, 5, '', 'BL', 0, 'C');
        $fpdf->CellFitScale(10, 5, '', 'BL', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'INR', 'BL', 0, 'C');
        $fpdf->CellFitScale(20, 5, 'INR', 'BL', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'INR', 'BL', 0, 'C');
        $fpdf->CellFitScale(20, 5, 'value INR', 'BL', 0, 'C');
        $fpdf->CellFitScale(10, 5, 'Rate', 'TBLR', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->CellFitScale(10, 5, 'Rate', 'TBLR', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->CellFitScale(15, 5, 'Amount INR', 'TBLR', 0, 'C');
        $fpdf->Ln();
        $fpdf->SetFont('courier', '', 8);
        $fpdf->SetFont('courier', '', 8);

        $itemTotal = $totalQty = $totalAmount = $totalDiscount = $totalTaxableValue = $totalSGST = $totalCGST = $grandTotal = 0;
        $quotationItem = QuotationProductDetail::with('getItemName')->where('quotation_id', $id)->get();
        $QuotationItemsDiscount = QuotationProductDetail::where('quotation_id', $id)->sum('discount_rate');

        $i = 1;
        $rowTotal = 28;
        foreach ($quotationItem as $value) {
            $total = ($value->rate) * ($value->qty);
            $fpdf->CellFitScale(8, 5, $i++, '1', 0, 'C');
            $fpdf->CellFitScale(54, 5, $value->getItemName->name, '1', 0, 'L');
            $fpdf->CellFitScale(10, 5, $value->qty, 1, 0, 'C');
            $fpdf->CellFitScale(15, 5, $value->rate, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $total, 1, 0, 'R');
            $fpdf->CellFitScale(15, 5, $value->discount_amount, 1, 0, 'R');
            $fpdf->CellFitScale(20, 5, $value->taxable_value, 1, 0, 'R');
            $fpdf->CellFitScale(10, 5, $value->taxrate, '1', 0, 'R');
            $fpdf->CellFitScale(15, 5, $value->sgst_amount, 1, 0, 'R');
            $fpdf->CellFitScale(10, 5, $value->taxrate, 1, 0, 'R');
            $fpdf->CellFitScale(15, 5, $value->cgst_amount, 1, 0, 'R');
            $fpdf->CellFitScale(15, 5, $value->item_total_amount, 1, 1, 'R');

            if (!empty($value->p_description)) {
                $fpdf->CellFitScale(207, 5, $value->p_description, 1, 1, 'L');
            }

            $itemTotal = $itemTotal + $value->item_total_amount;
            $totalQty += $value->qty;
            $totalAmount += $total;
            $totalDiscount += $value->discount_amount;
            $totalTaxableValue += $value->taxable_value;
            $totalSGST += $value->sgst_amount;
            $totalCGST += $value->cgst_amount;
            $grandTotal += $value->item_total_amount;
            $rowTotal -= 2;
        }
        for ($t = 1; $t <= $rowTotal; $t++) {
            $fpdf->CellFitScale(8, 5, '', 'LR', 0, 'C');
            $fpdf->CellFitScale(54, 5, '', 'LR', 0, 'L');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'C');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(20, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(10, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(15, 5, '', 'LR', 0, 'R');
            $fpdf->CellFitScale(50, 5, '', 'LR', 1, 'R');


        }

        $grand_total = $grand_qty = 0;
        $totalQty = $totalQty;
        $totalAmount = $totalAmount;
        $totalDiscount = $totalDiscount;
        $totalTaxableValue = $totalTaxableValue;
        $totalCGST = $totalCGST;
        $totalSGST = $totalSGST;
        $grandTotal = $grandTotal;


        $fpdf->SetWidths(array(8, 54, 10, 15, 20, 15, 20, 10, 15, 10, 15, 15, 50));
        $fpdf->Row(array('', 'Sub Total', $totalQty, '', number_format($totalAmount, 2), number_format($totalDiscount, 2), number_format($totalTaxableValue, 2), '', number_format($totalSGST, 2), '', number_format($totalCGST, 2), number_format($grandTotal, 2)), array('C', 'L', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'), false, '', true);
        $fpdf->SetWidths(array(62, 10, 40, 20, 40, 30));
        $fpdf->SetFont('courier', '', 8);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFont('courier', 'B', 8);
        $fpdf->CellFitScale(122, 5, 'GSTIN No.:' . (!empty($quotation->customer->gst_no) ? $quotation->customer->gst_no : ''), 1, 0, 'L', true);
        $fpdf->CellFitScale(51, 5, 'Sub Total', 'TLB', 0, 'L', true);
        $fpdf->CellFitScale(34, 5, number_format($itemTotal, 2), 'TRB', 1, 'R', true);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array(62, 10, 40, 20, 40, 30));
        $fpdf->SetWidths(array(202));

        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->CellFitScale(28, 5, 'Bank Name :', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_name) ? $companyprofile->bank_name : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Tax Rate ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($quotation->pf_taxrate), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, 'Bank A/C No:', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_ac_no) ? $companyprofile->bank_ac_no : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'P & F ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($quotation->pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, 'RTGS/IFSC Code :', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, (!empty($companyprofile->bank_ifsc_code) ? $companyprofile->bank_ifsc_code : ''), 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Total With PF ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($quotation->total_with_pf), 2), 'R', 1, 'R');


        $fpdf->CellFitScale(28, 5, ' ', 'L', 0, 'L');
        $fpdf->CellFitScale(94, 5, ' ', 'R', 0, 'L');
        $fpdf->CellFitScale(51, 5, 'Rounding ', 'L', 0, 'L');
        $fpdf->CellFitScale(34, 5, number_format(($quotation->rounding_amount), 2), 'R', 1, 'R');


        $fpdf->SetFont('courier', '', 9);
        $fpdf->CellFitScale(17, 5, '', 'LB', 0, 'L');
        $fpdf->SetFont('courier', '', 9);
        $fpdf->CellFitScale(105, 5, '', 'RB', 0, 'L');
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(48, 5, 'Grand Total', 'LBT', 0, 'L', true);

        $fpdf->CellFitScale((37), 5, number_format(round($quotation->grand_total), 2), 'RBT', 1, 'R', true);;
        $fpdf->SetFillColor(255, 255, 255);

        $fpdf->SetWidths(array(207));
        $fpdf->Row(array('TOTAL AMOUNT IN WORDS INR: ' . strtoupper($this->conver_num_text_money(round($quotation->grand_total)))),
            array('L'), false, '', true);


        /* Footer Payment and condition*/
        if (!empty($quotation->term_condition)) {

            $fpdf->SetFont('courier', 'B', 9);
            $fpdf->CellFitScale(32, 5, "Freight", 'L', 0, 'L');
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->CellFitScale(190, 5, "Terms & Condition", 1, 1, 'L', true);
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->SetWidths([190]);
            $fpdf->SetFont('courier', 'B', 9);
            $fpdf->Row([$quotation->term_condition], ['L'], '', '', true, 3);

        }


        $quotationpayment = QuotationPayment::with('getPayment')->first();
        $fpdf->SetFont('courier', 'B', 9);
        $fpdf->CellFitScale(62, 5, "Dispatch Through", 'L', 0, 'L');
        $fpdf->CellFitScale(145, 5, ":   " . (!empty($quotation->dispatch_through) ? $quotation->dispatch_through : ''), 'R', 1, 'L');
        $fpdf->CellFitScale(62, 5, "Delivery Period", 'L', 0, 'L');
        $fpdf->CellFitScale(145, 5, ":   " . (!empty($quotation->delivery_period) ? $quotation->delivery_period : ''), 'R', 1, 'L');
        $fpdf->CellFitScale(62, 5, "Payment", 'L', 0, 'L');
        $fpdf->CellFitScale(145, 5, ":   " . (!empty($quotationpayment->getPayment->payment_terms) ? $quotationpayment->getPayment->payment_terms : ''), 'R', 1, 'L');
        $fpdf->CellFitScale(207, 5, "", 'T', 1);
        $fpdf->SetFillColor(255, 255, 255);

        if ($print_type == 'SendMail') {
            $file_name = $fpdf->Output('', 'S');
            return $file_name;
        } else if (($print_type == 'Quotation')) {
            $filename = date('ymdhis') . '.pdf';
            $fpdf->Output('telegram/' . $filename, 'F');
            return $filename;
        } else {
            $fpdf->Output();
        }
        exit();
    }


    public function sendEmail(Request $request)
    {
        $mail_title = $request->input('mail_title');
        $mail_body = $request->input('mail_body');
        $email = $request->input('email');
        $type = 'Quotation';
        $mail_quotation_id = $request->input('mail_quotation_id');
        $attachment = $request->input('attachment');

        if ($attachment == 'on') {
            $file_name = $this->printQuotation($mail_quotation_id, 'SendMail');

        } else {
            $file_name = '';
        }
        $details = [
            'title' => $mail_title,
            'body' => $mail_body,
            'attachment' => $file_name,
            'type' => $type
        ];
        Mail::to($email)->send(new \App\Mail\MyTestMail($details));
        $request->session()->flash('success', 'Email Sent Successfully');
        return redirect()->route('quotation.index');
    }


    public function convertNumberToWords($number = 0)
    {
        $no = round($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '',
            '1' => 'one',
            '2' => 'two',
            '3' => 'three',
            '4' => 'four',
            '5' => 'five',
            '6' => 'six',
            '7' => 'seven',
            '8' => 'eight',
            '9' => 'nine',
            '10' => 'ten',
            '11' => 'eleven',
            '12' => 'twelve',
            '13' => 'thirteen',
            '14' => 'fourteen',
            '15' => 'fifteen',
            '16' => 'sixteen',
            '17' => 'seventeen',
            '18' => 'eighteen',
            '19' => 'nineteen',
            '20' => 'twenty',
            '30' => 'thirty',
            '40' => 'forty',
            '50' => 'fifty',
            '60' => 'sixty',
            '70' => 'seventy',
            '80' => 'eighty',
            '90' => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }
        $str = array_reverse($str);
        $result = implode('', $str);


        return $result . "ONLY  ";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public
    function create($inquiry_id = null)
    {
        $quotationcountry = Country::all();
        $quotationcity = City::all();
        $quotationstate = State::all();
        $taxrate = TaxRate::all();
        $quotation = Inquiry::find($inquiry_id);
        $item = Item::all();

        $payment = PaymentTerms::all();
        $product_detail = QuotationProductDetail::all();
        $inquiryproductitems = [];

        if ($inquiry_id) {

            $inquiryproductArray = InquiryProduct::with('getItemName')->where('inquiry_id', $quotation->inquiry_id)->get();

            $inquiryproductitems = [];

            foreach ($inquiryproductArray as $Items) {
                array_push($inquiryproductitems, [
                    'inquiry_id' => $Items->inquiry_id,
                    'inquiry_product_id' => $Items->inquiry_product_id,
                    'item_id' => $Items->item_id,
                    'name' => $Items->getItemName->name,

                    'p_description' => $Items->p_description,
                    'qty' => $Items->qty,
                    'rate' => $Items->rate,
                    'taxrate' => $Items->taxrate,
                    'cgst_amount' => $Items->cgst_amount,
                    'sgst_amount' => $Items->sgst_amount,
                    'igst_amount' => $Items->igst_amount,
                    'taxable_value' => $Items->taxable_value,
                    'discount_rate' => $Items->discount_rate,
                    'item_total_amount' => $Items->item_total_amount,
                ]);
            }

        }
        return view('quotation.create')->with(compact('quotationstate', 'quotationcity', 'quotationcountry', 'payment', 'taxrate', 'quotation', 'item', 'inquiryproductitems', 'product_detail', 'inquiry_id'))->with('TY', 'I');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $financial = FinancialYear::where('is_default', 'Y')->first();
        $financialID = $financial->financial_year_id;
        $messages = [
            'customer_id.required' => 'Please Select Company Name',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        $prefix=CompanyProfile::first();
        $quotationPrefix=$prefix->quotation_prefix;

        $quotationNo = Quotation::max('quotation_no');
        $quotationNo += 1;

        Validator::make($request->all(), $rules, $messages)->validate();
        $inquiryInfo = Inquiry::find($request->post('inquiry_id'));
        $quotation = new Quotation();
        $quotation->customer_id = $request->post('customer_id');
        $quotation->financial_year_id = $financial->financial_year_id;
        $quotation->quotation_no = $quotationNo;
        $quotation->display_quotation_no =$quotationPrefix . '-'.str_pad($quotationNo, 4, '0', STR_PAD_LEFT);


        $quotation->inquiry_id = $request->post('inquiry_id');
        $quotation->state_id = $request->post('place_of_supply');
        $quotation->q_date = date('Y-m-d ', strtotime($request->post('q_date')));
        $quotation->reference = $request->post('reference');
        $quotation->contact_person = $request->post('contact_person');
        $quotation->phone_no = $request->post('phone_no');
        $quotation->email = $request->post('email');
        $quotation->gstin = $request->post('gstin');
        $quotation->created_id = Auth::user()->id;
        $quotation->dispatch_through = $request->post('dispatch_through');
        $quotation->delivery_period = $request->post('delivery_period');
        $quotation->save();
        if ($quotation->inquiry_id) {
            Inquiry::where('inquiry_id', $quotation->inquiry_id)->update(['inquiry_status' => 'Quotation Created']);
        }
        $QuotationID = $quotation->quotation_id;


        $billing_address = new QuotationBillingAddress();
        $billing_address->quotation_id = $QuotationID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->city_name = $request->post('billing_city');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();

        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address = new QuotationShippingAddress();
            $shipping_address->quotation_id = $QuotationID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $shipping_address->city_name = $request->post('billing_city');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address = new QuotationShippingAddress();
            $shipping_address->quotation_id = $QuotationID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->city_name = $request->post('shipping_city');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }


        $quotationpayment = new QuotationPayment();
        $quotationpayment->quotation_id = $QuotationID;
        $quotationpayment->payment_terms_id = $request->post('payment_terms_id');
        $quotationpayment->term_condition = $request->post('term_condition');
        $quotationpayment->notes = $request->post('notes');
        $quotationpayment->save();


        foreach ($request->post('grid_items') as $item) {
            $qty = empty($item['qty']) ? 0 : $item['qty'];
            $rate = empty($item['rate']) ? 0 : $item['rate'];
            $discountRate = empty($item['discount_rate']) ? 0 : $item['discount_rate'];
            $taxRate = empty($item['taxrate']) ? 0 : $item['taxrate'];
            $total = round(floatval($qty * $rate), 3);

            if ($request->post('discount_type') == 'P') {
                $discountAmount = round(((($total * $discountRate) / 100)), 3);
                $taxableValue = round(floatval($total) - $discountAmount, 3);
                $gstAmount = round(floatval((($taxableValue * $taxRate) / 100)), 3);
                $igst = round(floatval(($taxableValue * $taxRate) / 100), 3);
                $cgst = round(floatval($igst / 2), 3);
                $sgst = round(floatval($igst / 2), 3);
                $TotalAmount = round(floatval($taxableValue + $gstAmount), 3);

            } else {
                $discountAmount = round((($discountRate)), 3);
                $taxableValue = round(floatval($total) - $discountAmount, 3);
                $gstAmount = round(floatval((($taxableValue * $taxRate) / 100)), 3);
                $igst = round(floatval(($taxableValue * $taxRate) / 100), 3);
                $cgst = round(floatval($igst / 2), 3);
                $sgst = round(floatval($igst / 2), 3);
                $TotalAmount = round(floatval($taxableValue + $gstAmount), 3);
            }

            /** Stop Tax Calculation */

            /** invoiceitems table save */
            $product_detail = new QuotationProductDetail();
            $product_detail->quotation_id = $QuotationID;
            $product_detail->item_id = $item['item_id'];
            $product_detail->p_description = $item['p_description'];
            $product_detail->qty = $qty;
            $product_detail->rate = $rate;
            $product_detail->discount_rate = $discountRate;
            $product_detail->discount_type = 'p';
            $product_detail->discount_amount = $discountAmount;
            $product_detail->taxable_value = $taxableValue;
            $product_detail->taxrate = $taxRate;
            $product_detail->gst_amount = $gstAmount;
            $product_detail->cgst_amount = $cgst;
            $product_detail->igst_amount = $igst;
            $product_detail->sgst_amount = $sgst;
            $product_detail->item_total_amount = $TotalAmount;
            $product_detail->save();
        }

        $itemSumTotal = DB::table('quotation_product_detail')->where('quotation_id', $quotation->quotation_id)->sum('item_total_amount');
        $pf = $request->post('pf');
        $pftaxrate = $request->post('pf_taxrate');
        $pftaxablevalue = (($pf * $pftaxrate) / 100);
        $totalamount = $itemSumTotal;
        $totalpf = (($pf + $pftaxablevalue) + $totalamount);
        $RoundingAmount = $request->post('rounding_amount');
        if ($RoundingAmount > 0) {
            $totalpf + 1;

        } else {
            $totalpf - 1;
        }
        $GrandTotal = ($totalpf + $RoundingAmount);
        Quotation::where('quotation_id', $quotation->quotation_id)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);


        $request->session()->flash('success', 'quotation created successfully');
        return redirect()->route('quotation.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Quotation $quotation
     * @return \Illuminate\Http\Response
     */
    public
    function show(Quotation $quotation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Quotation $quotation
     * @return \Illuminate\Http\Response
     */
    public
    function edit($ID)
    {
        $quotation = Quotation::find($ID);

        $taxrate = TaxRate::all();
        $quotationpayment = QuotationPayment::where('quotation_id', $quotation->quotation_id)->first();
        $quotationstate = State::all();
        $quotationcity = City::all();
        $quotationcountry = Country::all();
        $payment = PaymentTerms::all();
        $billing_address = QuotationBillingAddress::where('quotation_id', $quotation->quotation_id)->first();

//        echo "<pre>";
//        print_r($billing_address);exit();

        $shipping_address = QuotationShippingAddress::where('quotation_id', $quotation->quotation_id)->first();


        $item = Item::all();
        $inquiryproductArray = QuotationProductDetail::with('getItemName')->where('quotation_id', $quotation->quotation_id)->get();

        $inquiryproductitems = [];
        foreach ($inquiryproductArray as $Items) {
            array_push($inquiryproductitems, [
                'quotation_id' => $Items->quotation_id,
                'product_detail_id' => $Items->product_detail_id,
                'item_id' => $Items->item_id,
                'name' => $Items->getItemName->name,
                'p_description' => $Items->p_description,
                'qty' => $Items->qty,
                'rate' => $Items->rate,
                'taxrate' => $Items->taxrate,
                'cgst_amount' => $Items->cgst_amount,
                'sgst_amount' => $Items->sgst_amount,
                'igst_amount' => $Items->igst_amount,
                'taxable_value' => $Items->taxable_value,
                'discount_rate' => $Items->discount_rate,
                'item_total_amount' => $Items->item_total_amount,
            ]);
        }

        return view('quotation.create')->with(compact('quotationstate', 'quotationcountry', 'quotationcity', 'payment', 'quotationpayment', 'taxrate', 'item', 'inquiryproductitems', 'quotation', 'billing_address', 'shipping_address'))->with('TY', 'U');


    }

    public
    function sendTelegram(Request $request, $quotation_id)
    {

        $userInfo = DB::table('users')->where('id', Auth::user()->id)->first();
        $telegramID = $userInfo->telegram_id;
        $file_name = $this->printQuotation($quotation_id, 'Quotation');
        Notification::route('telegram', $telegramID)
            ->notify(new TelegramNotification($quotation_id, 'Quotation', $file_name));
        unlink('./telegram/' . $file_name);
        $request->session()->flash('success', 'Message Sent Successfully..');
        return redirect()->route('quotation.index');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Quotation $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)
    {
        $messages = [
            'customer_id.required' => 'Please Select Company Name',
            'grid_items.*.item_id.required' => 'Select Product',
            'grid_items.*.qty.required' => 'Qty required',

        ];
        $rules = [
            'customer_id' => 'required',
            'grid_items.*.item_id' => 'required',
            'grid_items.*.qty' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        $quotation = Quotation::find($ID);
        $quotation->customer_id = $request->post('customer_id');
        $quotation->q_date = date('Y-m-d ', strtotime($request->post('q_date')));
        $quotation->reference = $request->post('reference');
        $quotation->contact_person = $request->post('contact_person');
        $quotation->phone_no = $request->post('phone_no');
        $quotation->email = $request->post('email');
        $quotation->gstin = $request->post('gstin');
        $quotation->state_id = $request->post('place_of_supply');
        $quotation->updated_id = Auth::user()->id;
        $quotation->dispatch_through = $request->post('dispatch_through');
        $quotation->delivery_period = $request->post('delivery_period');

        $quotation->save();

        $QuotationID = $quotation->quotation_id;


        QuotationPayment::where('quotation_id', $ID)->update(['payment_terms_id' => $request->post('payment_terms_id'), 'term_condition' => $request->post('term_condition'), 'notes' => $request->post('notes')]);
        QuotationProductDetail::where('quotation_id', $quotation->quotation_id)->delete();
        foreach ($request->post('grid_items') as $item) {
            $qty = empty($item['qty']) ? 0 : $item['qty'];
            $rate = empty($item['rate']) ? 0 : $item['rate'];
            $discountRate = empty($item['discount_rate']) ? 0 : $item['discount_rate'];
            $taxRate = empty($item['taxrate']) ? 0 : $item['taxrate'];
            $total = round(floatval($qty * $rate), 3);

            if ($request->post('discount_type') == 'P') {
                $discountAmount = round(((($total * $discountRate) / 100)), 3);
                $taxableValue = round(floatval($total) - $discountAmount, 3);
                $gstAmount = round(floatval((($taxableValue * $taxRate) / 100)), 3);
                $igst = round(floatval(($taxableValue * $taxRate) / 100), 3);
                $cgst = round(floatval($igst / 2), 3);
                $sgst = round(floatval($igst / 2), 3);
                $TotalAmount = round(floatval($taxableValue + $gstAmount), 3);

            } else {
                $discountAmount = round((($discountRate)), 3);
                $taxableValue = round(floatval($total) - $discountAmount, 3);
                $gstAmount = round(floatval((($taxableValue * $taxRate) / 100)), 3);
                $igst = round(floatval(($taxableValue * $taxRate) / 100), 3);
                $cgst = round(floatval($igst / 2), 3);
                $sgst = round(floatval($igst / 2), 3);
                $TotalAmount = round(floatval($taxableValue + $gstAmount), 3);
            }


            /** Stop Tax Calculation */

            /** inquiryproduct table save */
            $product_detail = new QuotationProductDetail();
            $product_detail->quotation_id = $QuotationID;
            $product_detail->item_id = $item['item_id'];
            $product_detail->p_description = $item['p_description'];
            $product_detail->qty = $qty;
            $product_detail->rate = $rate;
            $product_detail->discount_rate = $discountRate;
            $product_detail->discount_type = 'p';
            $product_detail->discount_amount = $discountAmount;
            $product_detail->taxable_value = $taxableValue;
            $product_detail->taxrate = $taxRate;
            $product_detail->gst_amount = $gstAmount;
            $product_detail->cgst_amount = $cgst;
            $product_detail->igst_amount = $igst;
            $product_detail->sgst_amount = $sgst;
            $product_detail->item_total_amount = $TotalAmount;

            $product_detail->save();

        }
        $QuotationID = $quotation->quotation_id;
        $billing_address = QuotationBillingAddress::find($ID);
        $billing_address->quotation_id = $QuotationID;
        $billing_address->country_id = $request->post('country_id');
        $billing_address->state_id = $request->post('state_id');
        $billing_address->zip_code = $request->post('zip_code');
        $billing_address->address = $request->post('address');
        $billing_address->shipping_same_as_billing = ($request->input('shipping_same_as_billing') == 'Y') ? 'Y' : 'N';
        $billing_address->save();


        if ($billing_address->shipping_same_as_billing == 'Y') {
            $shipping_address = QuotationShippingAddress::find($ID);
            $shipping_address->quotation_id = $QuotationID;
            $shipping_address->country_id = $request->post('country_id');
            $shipping_address->state_id = $request->post('state_id');
            $shipping_address->shipping_pincode = $request->post('zip_code');
            $shipping_address->shipping_address = $request->post('address');
            $shipping_address->save();
        } elseif ($billing_address->shipping_same_as_billing == 'N') {
            $shipping_address = QuotationShippingAddress::find($ID);
            $shipping_address->quotation_id = $QuotationID;
            $shipping_address->country_id = $request->post('shipping_country_id');
            $shipping_address->state_id = $request->post('shipping_state_id');
            $shipping_address->shipping_pincode = $request->post('shipping_pincode');
            $shipping_address->shipping_address = $request->post('shipping_address');
            $shipping_address->save();
        }
        $itemSumTotal = DB::table('quotation_product_detail')->where('quotation_id', $quotation->quotation_id)->sum('item_total_amount');
        $pf = $request->post('pf');
        $pftaxrate = $request->post('pf_taxrate');
        $pftaxablevalue = (($pf * $pftaxrate) / 100);
        $totalamount = $itemSumTotal;
        $totalpf = (($pf + $pftaxablevalue) + $totalamount);
        $RoundingAmount = $request->post('rounding_amount');
        if ($RoundingAmount > 0) {
            $totalpf + 1;

        } else {
            $totalpf - 1;
        }
        $GrandTotal = ($totalpf + $RoundingAmount);
        Quotation::where('quotation_id', $quotation->quotation_id)->update(['total' => $totalamount, 'pf' => $pf, 'pf_taxrate' => $pftaxrate, 'total_with_pf' => $totalpf, 'rounding_amount' => $RoundingAmount, 'grand_total' => $GrandTotal]);


        $request->session()->flash('warning', 'Quotation updated successfully');
        return redirect()->route('quotation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Quotation $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation, Request $request)
    {
        if ($quotation->inquiry_id) {
            Inquiry::where('inquiry_id', $quotation->inquiry_id)->update(['inquiry_status' => 'Pending']);
        }
        $status = $message = '';
        if (Quotation::destroy($quotation->quotation_id)) {
            $status = 'error';
            $message = 'Inquiry deleted successfully.';
        } else {

            $status = 'info';
            $message = 'Inquiry failed to delete.';
        }
        $request->session()->flash($status, $message);
        return redirect()->route('quotation.index');


    }

    public
    function getQuotation(Request $request)
    {
        $quotatioPay = QuotationPayment::with('getPayment')->where('quotation_id', $request->input('quotationID'))->first();
        $quotation = Quotation::with('customer')->with('createdBy')->with('getPayment')->where('quotation_id', $request->input('quotationID'))->first();
        $quotationProducts = QuotationProductDetail::with('getItemName')->where('quotation_id', $request->input('quotationID'))->get();
        $html = '

<table class="table table-bordered table-sm">
  <tr>
    <th>Date</th>
    <td>' . date('d-m-Y', strtotime($quotation->q_date)) . '</td>
  </tr>
  <tr>
    <th>Customer</th>
    <td>' . $quotation->customer->company_name . '</td>
  </tr>
  <tr>
    <th>Contact Person </th>
    <td> ' . $quotation->customer_person . '</td>
  </tr>
   <tr>
    <th>Contact Mobile </th>
    <td> ' . $quotation->phone_no . '</td>
  </tr>
  <tr>
    <th>Contact Email  </th>
    <td> ' . $quotation->email . '</td>
  </tr>

</table>


                                            <div class="k-separator k-separator--border-dashed"></div>
                                            <div class="k-separator k-separator--height-sm"></div>
                                            <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm">
                                            <thead>
                                            <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Product Description</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Rate</th>

                                            <th class="text-center">Discount (' . ($quotationProducts[0]->discount_type) . ')</th>
                                            <th class="text-center">Taxable Value</th>
                                            <th class="text-center">Taxable Rate</th>
                                            <th class="text-center">CGST</th>
                                            <th class="text-center">SGST</th>
                                            <th class="text-center">IGST</th>
                                            <th class="text-center">Total</th>
</tr>
</thead>
<tbody class="small">';
        $counter = 1;
        foreach ($quotationProducts as $quotationProduct) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $counter . '</td>';

            $html .= '<td>' . (!empty($quotationProduct->getItemName->name) ? $quotationProduct->getItemName->name : '') . '</td>';
            $html .= '<td>' . $quotationProduct->p_description . '</td>';
            $html .= '<td class="text-center">' . $quotationProduct->qty . '</td>';
            $html .= '<td class="text-center">' . $quotationProduct->rate . '</td>';
            $html .= '<td class="text-center">' . $quotationProduct->discount_rate . '</td>';

            $html .= '<td class="text-center">' . number_format($quotationProduct->taxable_value, 2) . '</td>';
            $html .= '<td class="text-center">' . $quotationProduct->taxrate . '</td>';
            $html .= '<td class="text-center">' . number_format($quotationProduct->cgst_amount, 2) . '</td>';
            $html .= '<td class="text-center">' . number_format($quotationProduct->sgst_amount, 2) . '</td>';
            $html .= '<td class="text-center">' . number_format($quotationProduct->igst_amount, 2) . '</td>';
            $html .= '<td class="text-center">' . number_format($quotationProduct->item_total_amount, 2) . '</td>';
            $html .= '</tr>';
            $counter++;
        }
        $html .= '</tbody></table></div>
<div class="k-separator k-separator--border-dashed"></div>
                                            <div class="k-separator k-separator--height-sm"></div>
                                            <h5 class="k-section__title k-section__title-lg">Transport & Packaging</h5>

<table class="table table-bordered table-sm">
  <tr>
    <th>P&F Charge</th>
    <td>' . $quotation->pf_charges . '</td>
  </tr>
  <tr>
    <th>P&F Packing</th>
    <td>' . $quotation->pf_packing . '</td>
  </tr>
  <tr>
    <th>Freight</th>
    <td> ' . $quotation->freight . '</td>
  </tr>

  <tr>
    <th>Payment & Terms  </th>
    <td> ' . $quotatioPay->getPayment->payment_terms . '</td>
  </tr>
  <tr>
    <th>Terms & Condition  </th>
    <td> ' . $quotation->getPayment->term_condition . '</td>
  </tr>

</table>

                                            <div class="form-group row">
                                                <label class="col-3 col-form-label col-form-label-sm">Notes : ' . $quotatioPay->getPayment->notes . '</label>
                                            </div>
                                         ';

        return response($html);
    }

    public function getCustomer(Request $request)
    {


        $quotationID = $request->input('quotation_id');

        $quotation = DB::table('quotation')
            ->join('customer', 'customer.customer_id', '=', 'quotation.customer_id')
            ->select(['customer.company_name', 'quotation.quotation_id', 'quotation.customer_id', 'quotation.contact_person', 'quotation.email'])
            ->where('quotation.quotation_id', $quotationID)
            ->first();


        return response()->json(['quotation_id' => $quotation->quotation_id, 'customer_name' => $quotation->company_name, 'email' => $quotation->email, 'contact_person' => $quotation->contact_person], 200);
    }

    public function saveQuotation(Request $request)
    {

        $messages = [
            'remark.required' => 'Please Enter Remark',
            'next_followup_date.required' => 'Please Enter Date'
        ];
        $rules = [
            'remark' => 'required',
            'next_followup_date' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if (!$request->ajax()) {
            $validator->validate();
        } else {
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            }
        }

        $followup = new FollowUp();
        $followup->quotation_id = $request->input('quotation_id');
        $followup->created_id = Auth::user()->id;
        $followup->remark = $request->input('remark');
        $followup->next_followup_date = date('Y-m-d ', strtotime($request->post('next_followup_date')));

        $followup->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Follow Up successfully.', 'followup' => ['remark' => $followup->remark, 'next_followup_date' => $followup->next_followup_date, 'quotation_id' => $followup->quotation_id]]);
        } else {
            $request->session()->flash('success', 'Follow Up successfully');
            return redirect()->route('quotation.index');
        }
    }

    public
    function Quotationtimeline($quotation_id)
    {

        $followup = DB::table('followup')
            ->join('users', 'users.id', '=', 'followup.created_id')
            ->select(['users.name', 'followup.remark', 'followup.created_at'])
            ->where('followup.quotation_id', $quotation_id)
            ->get();


        return view('Inquiry.followup')->with(compact('followup', 'quotation_id'));
    }

    public
    function getTimeline(Request $request)
    {


        $quotationID = $request->input('quotation_id');
        $followup = DB::table('followup')
            ->join('users', 'users.id', '=', 'followup.created_id')
            ->select(['users.name', 'followup.remark', DB::raw("date_format(followup.created_at, '%d-%m-%Y %r')as created_at ")])
            ->where('followup.quotation_id', $quotationID)
            ->get()->toArray();


        return response()->json([$followup], 200);
    }

    public
    function getState()
    {
        $stateID = \request()->input('state_id');
        $quotationcountry = Country::where('state_id', $stateID)->get();
        $options = '<option value="">Select..</option>';
        foreach ($quotationcountry as $item) {
            $options .= '<option value="' . $item->id . '">' . $item->country_name . '</option>';
        }
        return response()->json(['quotationcountry' => $options], 200);
    }


    public
    function conver_num_text_money($number)
    {
        $no = round($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '',
            '1' => 'one',
            '2' => 'two',
            '3' => 'three',
            '4' => 'four',
            '5' => 'five',
            '6' => 'six',
            '7' => 'seven',
            '8' => 'eight',
            '9' => 'nine',
            '10' => 'ten',
            '11' => 'eleven',
            '12' => 'twelve',
            '13' => 'thirteen',
            '14' => 'fourteen',
            '15' => 'fifteen',
            '16' => 'sixteen',
            '17' => 'seventeen',
            '18' => 'eighteen',
            '19' => 'nineteen',
            '20' => 'twenty',
            '30' => 'thirty',
            '40' => 'forty',
            '50' => 'fifty',
            '60' => 'sixty',
            '70' => 'seventy',
            '80' => 'eighty',
            '90' => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ? "." . $words[$point / 10] . " " . $words[$point = $point % 10] : '';
        return $result . "ONLY  ";
    }
}
