<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        return $user->can('create_product');
    }

    public function update(User $user,Product $product)
    {
         $user->can('edit_product');
    }

    public function delete(User $user, Product $product)
    {
        return $user->can('delete_product');
    }

}
