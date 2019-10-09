<?php

use App\Permission;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionAdmin = Permission::whereName('create_customers', 'delete_customers', 'update_customers', 'create_users', 'delete_users', 'update_users')->first();
        $permission = Permission::find(1);
        // $customer->products()->save($product, [
        //    'selling_customer_price' => $request->input('selling_customer_price'),
        // $roleAdmin = Role::where('name', 'Administrator')->first();
        // $roleEditor = Role::where('name', 'Edytor')->first();

        $user = new User();
        $user->name = 'Andrzej';
        $user->surname = 'Zbroja';
        $user->is_seller = 1;
        $user->email = 'andrzej@zbroja.pl';
        $user->password = bcrypt('andrzej');
        $user->save();

        $user->permissions()->save($permission, [
            'permission_id' => '12',
        ]);

        $user->permissions()->save($permission, [
            'permission_id' => '11',
        ]);

        $user->permissions()->save($permission, [
            'permission_id' => '10',
        ]);

        $user->permissions()->save($permission, [
            'permission_id' => '9',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '8',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '7',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '6',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '5',
        ]);

        $user->permissions()->save($permission, [
            'permission_id' => '4',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '3',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '2',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '1',
        ]);

        $user = new User();
        $user->name = 'Zuzanna';
        $user->surname = 'Zbroja';
        $user->is_seller = 1;
        $user->email = 'zuza@zbroja.pl';
        $user->password = bcrypt('zuza');
        $user->save();

        $user->permissions()->save($permission, [
            'permission_id' => '12',
        ]);

        $user->permissions()->save($permission, [
            'permission_id' => '11',
        ]);

        $user->permissions()->save($permission, [
            'permission_id' => '10',
        ]);

        $user->permissions()->save($permission, [
            'permission_id' => '9',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '8',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '7',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '6',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '5',
        ]);

        $user->permissions()->save($permission, [
            'permission_id' => '4',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '3',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '2',
        ]);
        $user->permissions()->save($permission, [
            'permission_id' => '1',
        ]);

        // $user = new User();
        // $user->name = 'Andrzej';
        // $user->surname = 'Zbroja';
        // $user->email = 'andrzej@zbroja.pl';
        // $user->password = bcrypt('andrzej');
        // $user->save();

        // $user = new User();
        // $user->name = 'Karol';
        // $user->surname = 'Zbroja';
        // $user->email = 'karol@zbroja.pl';
        // $user->password = bcrypt('karol');
        // $user->save();

    }
}
