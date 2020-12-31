<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Tenant;
use App\User;
use DatabaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Events\TenantCreated;
use UserSeeder;
use Illuminate\Support\Facades\Event;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tenant = DB::table('tenants')
            ->join('domains', 'domains.tenant_id', '=', 'tenants.id')
            ->select(['domains.domain', 'tenants.id'])
            ->get();


        return view('main-view.tenant.index')->with(compact('tenant'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('main-view.tenant.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|max:15|unique:tenants,id',
        ]);

        $tenant = Tenant::create(['id' => $request->post('id')]);
        $tenant->domains()->create(['domain' => $request->post('id') . env('SET_DOMAIN','.localhost')]);

        $tenantID = $tenant->id;

//        Artisan::call("tenants:seed --tenants=$tenantID");

        return redirect()->route('tenant.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //:grimacing:
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {
        $tenant = Tenant::find($ID);
        return view('main-view.tenant.create')->with(compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tenant $tenant)
    {
        Tenant::destroy($tenant->id);
        return redirect()->route('tenant.index');

    }
}
