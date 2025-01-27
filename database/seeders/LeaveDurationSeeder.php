<?php

namespace Database\Seeders;

use App\Models\LeaveDuration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LeaveDuration::create(['name' => 'Half Day AM']);
        LeaveDuration::create(['name' => 'Half Day PM']);
        LeaveDuration::create(['name' => 'One or More Full Days']);
    }
}
