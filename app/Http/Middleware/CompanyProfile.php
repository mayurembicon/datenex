<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CompanyProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $company=DB::table('company_profile')->first();
        if(empty($company->company_name)){
            return redirect('profiles/create');
        }
        return $next($request);
    }
}
