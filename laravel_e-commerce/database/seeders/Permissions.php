<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array();
        $roles[] = array('name' => 'super', 'guard_name' => 'web',);
        $roles[] = array('name' => 'user', 'guard_name' => 'web',);
        $roles[] = array('name' => 'customer', 'guard_name' => 'customer',);
        foreach($roles as $role){
            Role::create([
                'name' => $role['name'],
                'guard_name' =>$role['guard_name']
            ]);
        }

        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_id' => 1,
            'model_type' => 'App\Models\User'
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => 2,
            'model_id' => 1,
            'model_type' => 'App\Models\User'
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => 2,
            'model_id' => 3,
            'model_type' => 'App\Models\Customer'
        ]);

        Permission::create(['name' => 'create_admin']);
        Permission::create(['name' => 'edit_admin']);
        Permission::create(['name' => 'delete_admin']);
        Permission::create(['name' => 'view_admin']);

        Permission::create(['name' => 'create_customer']);
        Permission::create(['name' => 'edit_customer']);
        Permission::create(['name' => 'delete_customer']);
        Permission::create(['name' => 'view_customer']);

        Permission::create(['name' => 'create_order']);
        Permission::create(['name' => 'edit_order']);
        Permission::create(['name' => 'delete_order']);
        Permission::create(['name' => 'view_order']);

        Permission::create(['name' => 'create_product']);
        Permission::create(['name' => 'edit_product']);
        Permission::create(['name' => 'delete_product']);
        Permission::create(['name' => 'view_product']);

        $admin = User::get()->first();
        $admin->assignRole('super');

        $role = Role::get()->first();
        $role->givePermissionTo(Permission::all());

        $roles = Permission::all()->pluck('id');
        foreach($roles as $role) {
            DB::table('model_has_permissions')->insert([
                'permission_id' => $role,
                'model_id' => 1,
                'model_type' => 'App\Models\User'
            ]);
        }
    }

}
