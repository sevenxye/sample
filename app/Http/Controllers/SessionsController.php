<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionsController extends Controller
{
    function __construct(){
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    
    //登录界面
    public function create(){
        return view('sessions.create');
    }

    //登录
    public function store(Request $request){
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        // 值
        // $credentials = [
        //     'email' => 'value',
        //     'password' => 'value'
        // ];
        // Auth::attempt(['email'=>$email,'password'=>$password]){}
        if(Auth::attempt($credentials, $request->has('remember'))){
            //登录成功后相关操作
            session()->flash('success', '欢迎回来');
            return redirect()->route('users.show', [Auth::user()]);
        }else{
            //登录失败后相关操作
            session()->flash('danger', '很抱歉，你的邮箱和密码不匹配');
            return redirect()->back();
        }

        return;
    }

    //退出
    public function destroy(){
        Auth::logout();
        session()->flash('success', '您已成功退出');
        return redirect('login');
    }
}
