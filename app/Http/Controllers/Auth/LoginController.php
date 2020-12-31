<?php

namespace App\Http\Controllers\Auth;

use App\CompanyProfile;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
//        $databaseName = DB::connection()->getDatabaseName();
//
//        $companyInfo = CompanyProfile::first();
//
//        if (!$databaseName['database']=="daten_'...'"){
//    }

//        view()->composer('auth.login', function ($view) {
//            $companyInfo = CompanyProfile::first();
//            if ($companyInfo) {
//                return $view->with(compact('companyInfo'));
//            }
//        });
    }
}
