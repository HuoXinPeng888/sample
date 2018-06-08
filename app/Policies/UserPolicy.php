<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

//    修改用户信息

    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

//    删除用户
    public function del(User $nowuser,User $needpower)
    {
        return $nowuser->is_admin && $nowuser->id !== $needpower->id;
    }

}

