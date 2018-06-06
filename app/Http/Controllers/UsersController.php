<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:10',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        session()->flash('success','注册成功');
        return redirect()->route('users.show',[$user]);
    }

    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

}
