<?php

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
        $this->call(PermissionTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        // $this->call(CustomerTableSeeder::class);
        // $this->call(InvoiceTableSeeder::class);
        //$this->call(InvoiceProductTableSeeder::class);

    }
}
