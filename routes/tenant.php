<?php

declare(strict_types=1);

use App\Http\Middleware\CompanyProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\Profile;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::middleware([
        CompanyProfile::class,
    ])->group(function () {


        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/', 'HomeController@index')->name('home');
        Auth::routes();

        Route::resource('user', 'UserController');
        Route::resource('user-profile', 'UserProfileController');
        Route::resource('category', 'CategoryController');
        Route::resource('invoice', 'InvoiceController');
        Route::post('get-category-items', 'InvoiceController@getCategoryItems');
        Route::post('get-itemmaster-information', 'ItemController@getItemInformation');
        Route::resource('invoice', 'InvoiceController');



        Route::resource('company', 'CompanyController');
        /*  end User and Company Route */

        Route::resource('customer', 'CustomerController');
        Route::post('get-customer-data', 'CustomerController@getCustomerdata');
        Route::post('search-customer', 'CustomerController@searchCustomer');
        Route::get('clear-customer', 'CustomerController@clearCustomer');

        /*  Start item inquiryinquiry Route */
        Route::post('item-rating', 'ItemController@Itemrating');
        Route::resource('item-master', 'ItemController');
        Route::post('search-item', 'ItemController@searchItem');
        Route::get('clear-item', 'ItemController@clearItem');
        /*  End item inquiry Route */

        /*  Start online inquiry Route */
        Route::resource('online-inquiry', 'OnlineInquiryController');
        Route::get('sync-inquiry/{inquiryFrom}', 'OnlineInquiryController@syncInquiry');
        Route::get('get-online-inquiry-data', 'OnlineInquiryController@getOnlineInquiryData');
        Route::get('make-inquiry/{OID}', 'OnlineInquiryController@makeInquiry');
        Route::post('search-online-inquiry', 'OnlineInquiryController@searchInquiry');
        Route::get('clear-online-inquiry', 'OnlineInquiryController@clearInquiry');
        Route::post('oi-follow-up', 'OnlineInquiryController@save');
        Route::post('get-online-customer-info', 'OnlineInquiryController@getCustomerInfo');

        Route::post('get-oi-info', 'OnlineInquiryController@getOiTransaction');

        /*  End online inquiry Route */

        /* Start Quotation */
        Route::get('make-quotation/{inquiry_id?}', 'QuotationController@create');
        Route::post('get-quotation-info', 'QuotationController@getQuotation');
        Route::post('send-email', 'QuotationController@sendEmail');

        Route::get('quotation-print/{id}', 'QuotationController@printQuotation');

        Route::post('get-timeline', 'QuotationController@getTimeline');

        /* end */

        /*  Start Inquiry Route */
        Route::resource('inquiry', 'InquiryController');
        Route::get('send-telegram-msg/{inquiry_id}', 'InquiryController@sendTelegram');
        Route::get('timeline/{inquiry_id}', 'InquiryController@timeline');
        Route::post('get-inquiry-timeline', 'InquiryController@getInquiryTimeline');
        Route::post('get-composition-items', 'InquiryController@getCompositionItems');
        Route::post('follow-up', 'InquiryController@save');
        Route::post('assign', 'InquiryController@assign');
        Route::post('get-last-user', 'InquiryController@getUser');
        Route::post('rating', 'InquiryController@rating');
        Route::post('get-inquiry-info', 'InquiryController@getInquiry');
        Route::post('search-inquiry', 'InquiryController@Search');
        Route::get('clear-inquiry', 'InquiryController@Clear');
        Route::get('inquiry-close/{inquiry_id?}', 'InquiryController@InquiryClose');
        Route::get('inquiry-active/{inquiry_id?}', 'InquiryController@inquiryActive');


        /*  End Inquiry Route */


        /* start itemmaster Route */
        Route::resource('item-master', 'ItemController');
        Route::post('get-item-data', 'ItemController@getItemInformation');
        /*  End itemmaster Route */


        /* start Purchase and Po Route */
        Route::get('make-purchase/{po_id?}', 'PurchaseController@create');
        Route::resource('purchase', 'PurchaseController');
        Route::post('search-purchase', 'PurchaseController@searchPurchase');
        Route::get('clear-purchase', 'PurchaseController@clearPurchase');
        Route::get('send-purchase-telegram-msg/{quotation_id}', 'PurchaseController@sendTelegram');


        Route::resource('po', 'PurchaseOrderController');
        Route::post('search-po', 'PurchaseOrderController@searchPo');
        Route::get('clear-po', 'PurchaseOrderController@clearPo');
        Route::get('send-po-telegram-msg/{quotation_id}', 'PurchaseOrderController@sendTelegram');


        Route::post('po-customer-email', 'PurchaseOrderController@getCustomer');
        Route::post('send-po-email', 'PurchaseOrderController@sendEmail');
        Route::get('print-po/{po_id}', 'PurchaseOrderController@printPO');
        Route::get('prints-po/{po_id}', 'PurchaseOrderController@print');
        /* end  Purchase and Po Route */


        /*  Start quotation Route */
        Route::resource('quotation', 'QuotationController');
        Route::post('search-quotation', 'QuotationController@searchQuotation');
        Route::get('clear-quotation', 'QuotationController@clearQuotation');
        Route::post('get-customer', 'QuotationController@getCustomer');
        Route::post('quotation-follow-up', 'QuotationController@saveQuotation');
        Route::get('q-timeline/{quotation_id}', 'QuotationController@Quotationtimeline');
        Route::get('print-quotation/{quotation_id}', 'QuotationController@printQuotation');
        Route::post('get-state-items','QuotationController@getState');
        Route::get('send-quotation-telegram-msg/{quotation_id}', 'QuotationController@sendTelegram');



        /* end quotation Route */


        /*  Start Sales Route */
        Route::get('make-sales/{transactionID}/{transactionType}', 'SalesController@create');

        Route::resource('sales', 'SalesController');
        Route::post('search-sales', 'SalesController@searchSales');
        Route::get('clear-sales', 'SalesController@clearSales');
        Route::get('print-sales/{invoice_id}', 'SalesController@printInvoice');
        Route::get('print/{invoice_id}', 'SalesController@print');
        Route::post('sales-customer-email', 'SalesController@getCustomer');
        Route::post('send-sales-email', 'SalesController@sendEmail');
        Route::get('send-sales-telegram-msg/{quotation_id}', 'SalesController@sendTelegram');

        /*  End Sales Route */

        /*  Start Pi Route */
        Route::get('make-pi/{quotation_id?}', 'PiController@create');
        Route::resource('pi', 'PiController');
        Route::post('search-pi', 'PiController@searchPi');
        Route::get('clear-pi', 'PiController@clearPi');
        Route::post('get-pi-customer', 'PiController@getPiCustomer');
        Route::post('pi-follow-up', 'PiController@savePi');
        Route::get('pi-timeline/{pi_id}', 'PiController@Pitimeline');
        Route::post('get-pi-timeline', 'PiController@getTimeline');

        Route::post('get-pi-info', 'PiController@getPiTransaction');
        Route::get('print-pi/{piID}', 'PiController@printPI');
        Route::post(' get-customer-email', 'PiController@getCustomer');
        Route::post('send-pi-email', 'PiController@sendEmail');
        Route::get('send-pi-telegram-msg/{quotation_id}', 'PiController@sendTelegram');






        /*  End Pi Route */

        /*  Start Report  Route */
        Route::resource('report-inquiry', 'Report\InquiryReportController');
        Route::resource('itemwise-sp', 'Report\ItemWiseSPController');
        Route::resource('customerwise-sp', 'Report\CcustomerWiseSPController');
        Route::resource('stock', 'Report\customerList');
        Route::resource('report-quotation', 'Report\QuotationReportController');
        Route::resource('report-pi', 'Report\PiReportController');
        Route::resource('report-invoice', 'Report\InvoiceReportController');

        /*  End  Report   Route */

        Route::post('c-timeline-create', 'CustomerTimelineController@create');
        Route::get('c-timeline-index', 'CustomerTimelineController@index');
        Route::post('customer-list', 'Common\CommonController@customerList');
        Route::post('invoice-no-list', 'Common\CommonController@getInvoiceNo');

        Route::post('state-list', 'Common\CommonController@stateList');
        Route::post('country-list', 'Common\CommonController@countryList');
        Route::post('item-list', 'Common\CommonController@itemList');



        /*  Start Sales Return Route */
        Route::resource('sales-return', 'SalesReturnController');
        Route::post('search-sr', 'SalesReturnController@searchSalesReturn');
        Route::get('clear-sr', 'SalesReturnController@clearSalesReturn');

        /*  End Sales Return  Route */

        /*  Start Purchase Return Route */
        Route::resource('purchase-return', 'PurchaseReturnController');
        Route::post('search-pr', 'PurchaseReturnController@searchPurchaseReturn');
        Route::get('clear-pr', 'PurchaseReturnController@clearPurchaseReturn');
        /*  End Purchase Return  Route */


        Route::resource('financial-year', 'FinancialYearController');
        Route::resource('customer-inquiry', 'CustomerInquiryController');
        Route::post('search-customer-inquiry', 'CustomerInquiryController@searchCustomerInquiry');
        Route::get('clear-customer-inquiry', 'CustomerInquiryController@clearCustomerInquiry');
        Route::post('customer-info', 'CustomerInquiryController@customerDetail');
        Route::post('ci-follow-up', 'CustomerInquiryController@save');
        Route::post('get-customer-items', 'CustomerInquiryController@getCustomerInfomation');

        Route::get('create-inquiry/{OID}', 'CustomerInquiryController@createInquiry');
        Route::post('get-ci-info', 'CustomerInquiryController@getCiTransaction');

        Route::get('send-telegram/{urlType}', 'CustomerInquiryController@sendTelegram');
        Route::post('send-email-link', 'CustomerInquiryController@sendEmail');




        /*  Start FastMoving Route */

        Route::post('week', 'HomeController@weekWise');
        Route::post('month', 'HomeController@monthWise');
        Route::post('six-month', 'HomeController@sixMonth');
        Route::post('year', 'HomeController@oneYear');
        /*  End FastMoving Route */

        /* journal Maintain  Route Start*/
        Route::resource('payment', 'JournalController');
        Route::post('search-py', 'JournalController@searchPayment');
        Route::get('clear-py', 'JournalController@clearPayment');

        Route::resource('receipt', 'ReceiptController');
        Route::post('search-rc', 'ReceiptController@searchReceipt');
        Route::get('clear-rc', 'ReceiptController@clearReceipt');

        Route::get('outstanding-balance', 'Report\OutstandingBalanceController@index');
        Route::get('ledger-list', 'Report\ReportLedgerListController@index');
        Route::post('outstanding-balance-report', 'Report\OutstandingBalanceController@create');
        Route::get('customer-deteils/{id?}', 'Report\OutstandingClientTransactionController@ClientTransaction');
        Route::post('index-serach-list', 'Common\CommonController@indexSearchList');

        /* end Route */

        /* Setting Route Start*/
        Route::resource('setting', 'SettingController');
        /* end Setting */
    });
    Route::resource('profiles', 'CompanyProfileController');
    Route::get('password-change', 'UserProfileController@createChangePass');

    Route::post('state-list', 'Common\CommonController@stateList');
    Route::post('country-list', 'Common\CommonController@countryList');
    Route::post('placeofsupply-list', 'Common\CommonController@placeofsupply');

    Route::get('edit-profile','UserProfileController@editProfile');
    Route::any('change-password','UserProfileController@changePassword');

});


