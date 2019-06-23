<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Status;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaticPolicy
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

    //删除操作
    public function delete(User $currentUser, Status $status){
        return $currentUser->id === $status->user_id;
    }
}
