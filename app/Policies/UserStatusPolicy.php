<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserStatusPolicy
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

    public function destroy(User $user, UserStatus $status)
    {
        return $user->id === $status->user_id;
    }
}
