<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    //註冊頁面
    public function create(){
        return view('users.create');
    }

    //显示用户信息
    public function show(User $user){
        return view('users.show', compact('user'));
    }
}
