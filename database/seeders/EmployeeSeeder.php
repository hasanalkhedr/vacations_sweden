<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Supervisor 1
        $employee = Employee::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('123456'),
            'department_id' => 1
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(1);
        $department['manager_id'] = $employee['id'];
        $department->save();

        // Supervisor 2
        $employee = Employee::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'password' => Hash::make('123456'),
            'department_id' => 2
        ]);
        $role = Role::findByName('employee');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(2);
        $department['manager_id'] = $employee['id'];
        $department->save();

        // Regular Employee 1
        $employee = Employee::create([
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'email' => 'alice.johnson@example.com',
            'password' => Hash::make('123456'),
            'department_id' => 1
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Regular Employee 2
        $employee = Employee::create([
            'first_name' => 'Bob',
            'last_name' => 'Brown',
            'email' => 'bob.brown@example.com',
            'password' => Hash::make('123456'),
            'department_id' => 2
        ]);
        $role = Role::findByName('employee');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // HR Employee
        $employee = Employee::create([
            'first_name' => 'Diana',
            'last_name' => 'Evans',
            'email' => 'diana.evans@example.com',
            'password' => Hash::make('123456'),
            'department_id' => 3
        ]);
        $role = Role::findByName('human_resource');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // SG Employee
        $employee = Employee::create([
            'first_name' => 'Eve',
            'last_name' => 'White',
            'email' => 'eve.white@example.com',
            'password' => Hash::make('123456'),
            'department_id' => 3
        ]);
        $role = Role::findByName('sg');
        $employee->can_submit_requests = true;
        $employee->save();
        $employee->roles()->save($role);

        // Head Employee
        $employee = Employee::create([
            'first_name' => 'Frank',
            'last_name' => 'Green',
            'email' => 'frank.green@example.com',
            'password' => Hash::make('123456'),
            'department_id' => 4
        ]);
        $role = Role::findByName('head');
        $employee->is_supervisor = true;
        $employee->can_submit_requests = false;
        $employee->save();
        $employee->roles()->save($role);
        $department = Department::find(4);
        $department['manager_id'] = $employee['id'];
        $department->save();
    }
}
