<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //更新操作
    public function update(User $currentUser, User $user){
        return $currentUser->id === $user->id; //返回true or false
    }

    //编辑操作
    public function edit(User $currentUser, User $user){
        return $currentUser->id === $user->id;
    }

    //删除操作
    public function delete(User $currentUser, User $user){
        return $currentUser->id !== $user->id && $currentUser->is_admin;
    }
}
