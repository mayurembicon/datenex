<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user =User::all();
        return view('User.index')->with('user',$user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('User.create')->with('action','INSERT');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required:user,name',
            'email' => 'required|string| email| max:50|unique:users,email',
            'password' => 'required:user,password',
            'role' => 'required:user,role',
        ]);
        $user= new User();
        $user->name = $request->post('name');
        $user->email=$request->post('email');
        $user->role=$request->post('role');
        $user->password=bcrypt($request->post('password'));
        $user->save();


        $request->session()->flash('warning', 'User Created successfully');
        return redirect()->route('user.index');
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
    public function edit(User $user)
    {
        return view('User.create')->with('user',$user)->with('action','UPDATE');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required:user,name',
            'email' => 'required|string| email| max:50|unique:users,email,'.$user->id.',id',
            'role' => 'required:user,role',



        ]);
        $user->name = $request->post('name');
        $user->email=$request->post('email');
        $user->role=$request->post('role');


        $user->save();
        $request->session()->flash('warning', 'User updated successfully');
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        User::destroy($user->id);
        return redirect()->route('user.index');
    }
}
