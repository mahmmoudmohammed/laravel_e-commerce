<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;


    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view_admin');
    }

    public function view(User $user, User $admin)
    {
        return  $user->can('view_admin');
    }

    public function store(User $user, User $admin)
    {
        return  $user->can('create_admin');
    }

    public function update(User $user)
    {
        return  $user->can('edit_admin');
    }

    public function delete(User $user)
    {
        return  $user->can('delete_admin');
    }
}
