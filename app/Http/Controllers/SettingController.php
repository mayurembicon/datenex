<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CompanyProfile;
use App\Inquiry;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::all();
        return view('setting.index')->with('setting', $setting);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'trade_user_id.required' => 'Please Enter User ID',
            'trade_profile_id.required' => 'Please Enter Profile ID',
            'trade_key.required' => 'Please Enter Key ',

            'india_mobile_no.required' => 'Please Enter Mobile No ',
            'india_key.required' => 'Please Enter Key ',
        ];
        $rules = [
            'trade_user_id' => 'required',
            'trade_profile_id' => 'required',
            'trade_key' => 'required',

            'india_mobile_no' => 'required',
            'india_key' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        Setting::create([
            'trade_user_id' => $request->post('trade_user_id'),
            'trade_profile_id' => $request->post('trade_profile_id'),
            'trade_key' => $request->post('trade_key'),


            'india_mobile_no' => $request->post('india_mobile_no'),
            'india_key' => $request->post('india_key'),


            'mail_body' => $request->post('mail_body'),
            'telegram_api' => $request->post('telegram_api'),
        ]);

        $request->session()->flash('success', 'Setting created successfully');
        return redirect()->route('setting.create');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        $companyInfo=\App\CompanyProfile::first();
        return view('company-profile.edit-settings')->with(compact('setting','companyInfo'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $messages = [
            'trade_user_id.required' => 'Please Enter User ID',
            'trade_profile_id.required' => 'Please Enter Profile ID',
            'trade_key.required' => 'Please Enter Key ',
            'indiamart_sync_time_limit' => 'Please Enter Time Limit',

            'india_mobile_no.required' => 'Please Enter Mobile No ',
            'india_key.required' => 'Please Enter Key ',

            'telegram_api.required' => 'Please Enter Telegram Api Id ',
        ];
        $rules = [
            'trade_user_id' => 'required',
            'trade_profile_id' => 'required',
            'trade_key' => 'required',

            'indiamart_sync_time_limit' => 'required',

            'india_mobile_no' => 'required',
            'india_key' => 'required',

            'telegram_api' => 'required',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();

        $setting->trade_user_id = $request->post('trade_user_id');
        $setting->trade_profile_id = $request->post('trade_profile_id');
        $setting->trade_key = $request->post('trade_key');


        $setting->indiamart_sync_time_limit = $request->post('indiamart_sync_time_limit');


        $setting->india_mobile_no = $request->post('india_mobile_no');
        $setting->india_key = $request->post('india_key');


        $setting->mail_body = $request->post('mail_body');
        $setting->telegram_api = $request->post('telegram_api');
        $setting->save();

        $request->session()->flash('success', 'Setting created successfully');
        return redirect()->route('setting.edit', 1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting, Request $request)
    {

    }
}
