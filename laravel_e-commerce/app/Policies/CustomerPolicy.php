<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->can('view_customer');
    }
    public function view(User $user,Customer $customer)
    {
        return  $user->can('view_customer');
    }

    public function store(User $user)
    {
        return $user->hasPermissionTo('create_customer');
    }

    public function update(User $user,Customer $customer)
    {
        $user->can('edit_customer');
    }

    public function delete(User $user, Customer $customer)
    {
        return $user->can('delete_customer');
    }
}
