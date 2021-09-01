<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "Super admin",
            'email' =>'super@gmail.com',
            'password' =>Hash::make('password12345'),
            'contact' => '01034686862',
        ]);
    }
}
