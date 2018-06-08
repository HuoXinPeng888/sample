<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }
//        用户登录
    public function store(Request $request)
    {
        $res = $this->validate($request,[
            'email' => 'required|email',
            'password'=>'required'
        ]);

        if (Auth::attempt($res,$request->has('remember'))){
            session()->flash('success','欢迎回来');
            return redirect()->intended(route('users.show',[Auth::user()]));
        }else{
            session()->flash('danger','邮箱或密码输入错误');
            return redirect()->back();
        }
    }
//        用户推出
    public function destroy()
    {
            Auth::logout();
            session()->flash('success','成功退出');
            return redirect('login');
    }
}
