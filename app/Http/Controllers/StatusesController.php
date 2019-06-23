<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusesController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }

    //创建微博
    public function store(Request $request){
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        return redirect()->back();
    }

    //删除微博
    public function destory(Status $status){
        $this->authorize('destory', $status);

        $status->delete();
        session()->flash('success', '删除成功');
        return redirect()->back();
    }
}
