<?php

namespace App\Http\Controllers;

use App\CompanyProfile;
use App\User;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class CompanyProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return view('company-profile.create');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company-profile.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $messages = [
            'company_name.required' => 'Please Enter Company Name',
            'c_logo.required' => 'Please Select Logo',
            'email.required' => 'Please Enter User Name',
            'password.required' => 'Please Enter Password',


        ];
        $rules = [
            'company_name' => 'required',
            'c_logo' => 'required',
            'email' => 'required',
            'password' => 'required',

        ];
        Validator::make($request->all(), $rules, $messages)->validate();


        $image = \request()->file('c_logo');
        if(!empty($image)) {
            $path = 'profile';
            $image->move(public_path($path), $image->getClientOriginalName());
            $file_name = $image->getClientOriginalName();
        }else{
            $file_name = '';
        }

        $companyInfo=new CompanyProfile();
        $companyInfo->company_name = $request->post('company_name');
        $companyInfo->c_logo = $file_name;
        $companyInfo->gst_in = $request->post('gst_in');
        $companyInfo->address1 = $request->post('address1');
        $companyInfo->address2 = $request->post('address2');
        $companyInfo->address3 = $request->post('address3');
        $companyInfo->phone_no = $request->post('phone_no');
        $companyInfo->pincode = $request->post('pincode');
        $companyInfo->invoice_prefix = $request->post('invoice_prefix');
        $companyInfo->quotation_prefix = $request->post('quotation_prefix');
        $companyInfo->po_prefix = $request->post('po_prefix');
        $companyInfo->state_id = $request->post('state_id');
        $companyInfo->country_id = $request->post('country_id');
        $companyInfo->city_name = $request->post('city_name');
        $companyInfo->save();


        $user= new User();
        $user->name = $request->post('company_name');
        $user->email=$request->post('email');
        $user->password=bcrypt($request->post('password'));
        $user->save();
        return redirect('/home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {
        $companyInfo=CompanyProfile::find($ID);

        return  view('company-profile.edit')->with(compact('companyInfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ID)
    {

        $image = \request()->file('c_logo');
        if(!empty($image)) {
            $path = 'profile';
            $image->move(public_path($path), $image->getClientOriginalName());
            $file_name = $image->getClientOriginalName();
        }
        $companyInfo=CompanyProfile::find($ID);
        if(!empty($image)){
            if(!empty($companyInfo->c_logo)){
                if (file_exists('profile/' . $companyInfo->c_logo)) {
                    unlink('profile/' . $companyInfo->c_logo);
                }
            }
        }else{
            $file_name = $companyInfo->c_logo;
        }
        $companyInfo->company_name = $request->post('company_name');
        $companyInfo->c_logo = $file_name;
        $companyInfo->gst_in = $request->post('gst_in');
        $companyInfo->address1 = $request->post('address1');
        $companyInfo->address2 = $request->post('address2');
        $companyInfo->address3 = $request->post('address3');
        $companyInfo->phone_no = $request->post('phone_no');
        $companyInfo->pincode = $request->post('pincode');
        $companyInfo->invoice_prefix = $request->post('invoice_prefix');
        $companyInfo->quotation_prefix = $request->post('quotation_prefix');
        $companyInfo->po_prefix = $request->post('po_prefix');
        $companyInfo->state_id = $request->post('state_id');
        $companyInfo->country_id = $request->post('country_id');
        $companyInfo->city_name = $request->post('city_name');
        $companyInfo->save();
        return redirect('/home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
