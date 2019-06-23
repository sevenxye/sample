<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail;
use Auth;

class UsersController extends Controller
{
    //构造函数
    function __construct(){
        //登录验证
        $this->middleware('auth',[
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);

        //游客验证
        $this->middleware('guest', [
            'only' => ['create'],
        ]);
    }

    //用户列表
    public function index(){
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    //註冊頁面
    public function create(){
        return view('users.create');
    }

    //显示用户信息
    public function show(User $user){
        return view('users.show', compact('user'));
    }

    //创建用户
    public function store(Request $request){
        $flag = $this->validate($request,[
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // 注册成功自动登录
        // Auth::login($user);
        // session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        // return redirect()->route('users.show', [$user]);

        //注册成功，邮箱验证
        $this->sendEmailConfirmationTo($user);
        session()->flash('info', '请登录邮箱验证登录');
        return redirect('/');
        
    }

    //编辑用户
    public function edit(User $user){
        $this->authorize('edit', $user);
        return view('users.edit', compact('user'));
    }

    //更新用户
    public function update(Request $request, User $user){
        //验证数据
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        //更改成功提示
        session()->flash('success', '个人资料更新成功');

        return redirect()->route('users.show',[$user]);
        // return redirect()->route('users.show', $user->id);
    }

    //删除
    public function destroy(User $user){
        $this->authorize('delete', $user);
        $user->delete();
        session()->flash('success', '成功删除用户');
        return back();
    }

    //验证登录
    public function confirmEmail($token){
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '激活成功');
        return redirect()->route('users.show', [$user]);
    }

    //发送邮件验证
    protected function sendEmailConfirmationTo($user){
        $view = 'emails.confirm';
        $data = compact('user');
        $from = '921129767@qq.com';
        $name = 'seven';
        $to = $user->email;
        $subject = '登录验证';

        //日志记录，没有发件人
        // Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
        //     $message->from($from, $name)->to($to)->subject($subject);
        // });

        //配置发件人
        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });

    }
    
}
