<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SuperAdmin::class);
        $this->call(Products::class);
        $this->call(Customers::class);
        $this->call(Permissions::class);
    }
}
