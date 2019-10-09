<?php

use Illuminate\Database\Seeder;

use App\Role;
use App\User;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $role = new Role();
        $role->name = 'Administrator';
        $role->save();

        $role = new Role();
        $role->name = 'Edytor';
        $role->save();

        $role = new Role();
        $role->name = 'Użytkownik';
        $role->save();
           
                  

    }

}
