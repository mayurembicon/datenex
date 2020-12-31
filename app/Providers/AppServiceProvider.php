<?php

namespace App\Providers;

use App\CompanyProfile;
use App\CustomerInquiry;
use App\Inquiry;
use App\Observers\CustomerInquiryObserver;
use App\Observers\InquiryObserver;
use App\Observers\OnlineInquiryObserveer;
use App\Observers\PiObserver;
use App\Observers\PurchaseObserver;
use App\Observers\QuotationObserver;
use App\OnlineInquiry;
use App\Pi;
use App\PurchaseOrder;
use App\PurchaseProduct;
use App\Quotation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Inquiry::observe(InquiryObserver::class);
        Quotation::observe(QuotationObserver::class);
        Pi::observe(PiObserver::class);
        CustomerInquiry::observe(CustomerInquiryObserver::class);
        OnlineInquiry::observe(OnlineInquiryObserveer::class);
//        PurchaseProduct::observe(PurchaseObserver::class);
        view()->composer('layouts.app', function ($view) {
            $companyInfo = CompanyProfile::first();

            return $view->with(compact('companyInfo'));
        });

        }
}
