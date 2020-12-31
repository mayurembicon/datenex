<?php

namespace App\Http\Controllers;

use App\CompanyProfile;
use App\Rules\MatchOldPassword;
use App\User;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\Input;

class UserProfileController extends Controller
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
        $companyInfo =CompanyProfile::first();
        $id = Auth::user()->id;
        $user = User::find($id);
        $password = $user->password;
//        $decrypted = Crypt::decrypt($password);
//        print_r($decrypted);
//        exit();
//        $encryptedPassword = encrypt($password);
//        $decryptedPassword = decrypt($encryptedPassword);
//        $hashedPassword = Auth::user()->getAuthPassword();

//        $password == $decryptedPasswordl;

        return view('User.user_profile')->with('user', $user)->with('companyInfo',$companyInfo )->with('action', 'UPDATE');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($ID)
    {

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
        $validatedData = $request->validate([
            'name' => 'required:user,name',
            'email' => 'required|string| email| max:50|unique:users,email,' . $ID . ',id',
        ]);


        $user = User::find($ID);
        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->telegram_id = $request->post('telegram_id');
        $user->save();
        $request->session()->flash('warning', 'Profile Change successfully');
        return redirect()->route('user-profile.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);
        $request->session()->flash('warning', 'Password Change successfully');
        return redirect('home');
    }

    public function createChangePass()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $companyInfo = CompanyProfile::first();

        return view('company-profile.change-password')->with('user', $user)->with('action', 'UPDATE')->with('companyInfo',$companyInfo);

    }

    public function editProfile()

    {

        $companyInfo = CompanyProfile::first();
        return view('company-profile.perosanl-info')->with(compact('companyInfo'));
    }

    public function personalInfo()

    {
        $companyInfo = CompanyProfile::first();
        return view('company-profile.account-information')->with(compact('companyInfo'));
    }
}
