<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LeaveType::create(['name' => 'annual leave']);
        LeaveType::create(['name' => 'recovery']);
        LeaveType::create(['name' => 'assignment']);
        LeaveType::create(['name' => 'training']);
        LeaveType::create(['name' => 'sick leave']);
        LeaveType::create(['name' => 'bereavement leave']);
        LeaveType::create(['name' => 'maternity leave']);
        LeaveType::create(['name' => 'paternity leave']);
        LeaveType::create(['name' => 'marriage leave']);
        LeaveType::create(['name' => 'remote work']);
    }
}
