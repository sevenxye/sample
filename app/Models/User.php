<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //事件
    public static function boot(){
        parent::boot();
        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }

    //一个用户，拥有多条微博
    public function statuses(){
        return $this->hasMany(Status::class);
        //$this->hasMany(Status::class, foreign_key, local_key);
        //$this->hasMany(Status::class, user_id, id)
        //原生sql  select * from user u join statuses s on u.id = s.user_id 
    }

    //获取用户头像
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    //发送密码重置邮件
    public function sendPasswordResetNotification($token){
        $this->notify(new ResetPassword($token));
    }

    //当前用户发布过的微博
    public function feed(){
        return $this->statuses()->orderBy('created', 'desc');
    }
}
