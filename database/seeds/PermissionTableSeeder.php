<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permission = new Permission();
        $permission->name = 'create_customers';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'delete_customers';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'update_customers';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'create_users';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'delete_users';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'update_users';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'create_invoices';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'update_invoices';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'send_packages';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'regulation_invoices';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'manage_complaints';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'product';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'hours_raport';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'create_rubbishes';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'search_complaints';
        $permission->save();

    }

}
