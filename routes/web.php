<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

Route::middleware(['auth'])->group(function () {


    Route::get('/home', 'MainController\MainHomeController@index')->name('main-home');
    Route::get('/', 'MainController\MainHomeController@index')->name('main-home');
     Route::resource('tenant', 'MainController\TenantController');
//    Route::get('/', 'HomeController@index')->name('home');
//    /*  Start User and Company Route */
//    Route::resource('user', 'UserController');
//    Route::resource('category', 'CategoryController');
//    Route::resource('invoice', 'InvoiceController');
//    Route::post('get-category-items', 'InvoiceController@getCategoryItems');
//    Route::post('get-itemmaster-information', 'ItemController@getItemInformation');
//    Route::resource('customer', 'CustomerController');
//    Route::resource('invoice', 'InvoiceController');
//
//    Route::resource('company', 'CompanyController');
//    /*  end User and Company Route */
//
//    /*  Start item inquiry Route */
//    Route::post('item-rating', 'ItemController@Itemrating');
//
//    Route::resource('item-master', 'ItemController');
//
//    /*  End item inquiry Route */
//
//    /*  Start online inquiry Route */
//    Route::resource('online-inquiry', 'OnlineInquiryController');
//    Route::get('sync-inquiry/{inquiryFrom}', 'OnlineInquiryController@syncInquiry');
//    Route::get('get-online-inquiry-data', 'OnlineInquiryController@getOnlineInquiryData');
//    Route::get('make-inquiry/{OID}', 'OnlineInquiryController@makeInquiry');
//    /*  End online inquiry Route */
//
//    /* Start quotation */
//    Route::get('make-quotation/{inquiry_id?}', 'QuotationController@create');
//    Route::post('get-quotation-info', 'QuotationController@getQuotation');
//    /* end */
//
//    /*  Start Inquiry Route */
//    Route::resource('inquiry', 'InquiryController');
//    Route::get('timeline/{inquiry_id}', 'InquiryController@timeline');
//    Route::post('get-composition-items', 'InquiryController@getCompositionItems');
//    Route::post('follow-up', 'InquiryController@save');
//    Route::post('assign', 'InquiryController@assign');
//    Route::post('get-last-user', 'InquiryController@getUser');
//    Route::post('rating', 'InquiryController@rating');
//    Route::post('get-customer-data', 'InquiryController@getCustomerdata');
//    Route::post('get-item-data', 'InquiryController@getItemData');
//
//    Route::post('get-inquiry-info', 'InquiryController@getInquiry');
//    /*  End Inquiry Route */
//
//
//    Route::resource('item-master', 'ItemController');
//    Route::post('get-itemmaster-information', 'ItemController@getItemInformation');
//
//    Route::resource('customer', 'CustomerController');
//
//    /* start Purchase and Po Route */
//    Route::get('make-purchase/{po_id?}', 'PurchaseController@create');
//    Route::resource('purchase', 'PurchaseController');
//    Route::resource('po', 'PurchaseOrderController');
//
//    Route::post('get-itemmaster-information', 'PurchaseController@getItemMasterItems');
//    Route::post('get-itemmaster-information', 'PurchaseOrderController@getItemMasterItems');
//    /* end  Purchase and Po Route */
//
//
//    Route::resource('quotation', 'QuotationController');
//
//
//    /*  Start Invoice Route */
//    Route::get('make-invoice/{quotation_id?}', 'InvoiceController@create');
//    Route::resource('invoice', 'InvoiceController');
//    Route::post('get-customer-items', 'InvoiceController@getCustomerItems');
//    Route::post('get-itemmaster-information', 'InvoiceController@getItemMasterItems');
//    /*  End Invoice Route */
//
//    /*  Start Pi Route */
//    Route::get('make-pi/{quotation_id?}', 'PiController@create');
//    Route::resource('pi', 'PiController');
//    /*  End Pi Route */
//
//    /*  Start Report  Route */
//    Route::resource('report-inquiry', 'Report\InquiryReportController');
//    Route::resource('itemwise-sp', 'Report\ItemWiseSPController');
//    Route::resource('customerwise-sp', 'Report\CcustomerWiseSPController');
//    Route::resource('stock', 'Report\StockController');
//    /*  End  Report   Route */
//
//    Route::post('c-timeline-create', 'CustomerTimelineController@create');
//    Route::get('c-timeline-index', 'CustomerTimelineController@index');
//    Route::post('customer-list', 'Common\CommonController@customerList');
//    Route::post('item-list', 'Common\CommonController@itemList');
//
//
//    /*  Start Sales Return Route */
//    Route::resource('sales-return', 'SalesReturnController');
//    /*  End Sales Return  Route */
//
//    /*  Start Sales Return Route */
//    Route::resource('financial-year', 'FinancialYearController');
//
//    /*  End Sales Return  Route */


//    Route::resource('journal', 'JournalController');
//    Route::get('outstanding', 'OutstandingBalanceController@index');
//    Route::post('outstanding', 'OutstandingBalanceController@create');
//    Route::get('journal-customer', 'PaymentController@index');
//    Route::post('journal-customer', 'PaymentController@create');
//    Route::get('journal', 'JournalController@journal');
//    Route::get('journal-entry', 'JournalController@paymentCreate');
});
/*  Start Customer Inquiruy Route */
Route::resource('customer-inquiry', 'CustomerInquiryController');
/*  End  Customer Inquiruy  Route */

