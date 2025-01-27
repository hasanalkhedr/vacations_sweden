<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'employee']);
        $role->display_name = "Employee";
        $role->save();

        $role = Role::create(['name' => 'human_resource']);
        $role->display_name = "HR";
        $role->save();

        $role = Role::create(['name' => 'sg']);
        $role->display_name = "SG";
        $role->save();

        $role = Role::create(['name' => 'head']);
        $role->display_name = "IFL director";
        $role->save();

    }
}
