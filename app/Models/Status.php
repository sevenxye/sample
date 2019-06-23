<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['content'];
    
    //一条微博属于一个用户
    public function user(){
        return $this->belongsTo(User::class);
        //原生sql  select * from status s join user u on s.user_id = u.id;
        // return $this->belognsTo(User::class, user_id, id)
    }
}
