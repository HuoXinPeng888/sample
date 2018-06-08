<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;
class UsersController extends Controller
{
    public function __construct()
    {
//        Laravel 提供的 Auth 中间件在过滤指定动作时，如该用户未通过身份验证（未登录用户），默认将会被重定向到 /login 登录页面。
        $this->middleware('auth',[
            'except' => ['show','create','store','index']
        ]);

//        guest 和auth相反
        $this->middleware('guest',[
            'only' =>['create'],
        ]);
    }
    public function index()
    {
        $list = User::paginate(10);
        return view('users.index',compact('list'));
    }


//    注册用户
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
//        注册后自动登录
        Auth::login($user);
        session()->flash('success','注册成功');
        return redirect()->route('users.show',[$user]);
    }

    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    public function edit(User $user)
    {
//        用户只能编辑自己的信息
        try{
            $this->authorize('update', $user);
            return view('users.edit',compact('user'));
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }

    }
//        更新用户
    public function update(User $user,Request $request)
    {
        $this->validate($request,[
            'name'=> 'required|max:10',
            'password'=>'nullable|confirmed|min:6'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password){
            $data['password'] = $request->password;
        }
        $user->update($data);

        session()->flash('success','信息修改成功!');
        return redirect()->route('users.show',$user->id);
    }

//    删除用户
    public function destroy(User $user)
    {
//        使用 authorize 方法来对删除操作进行授权验证
        $this->authorize('del',$user);

        $user->delete();
        session()->flash('success','成功干掉了一个！');
        return back();
    }

}
