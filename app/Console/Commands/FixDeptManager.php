<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FixDeptManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign Department Manager to Josephine AbouRjeily';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $employee = Employee::firstWhere('id', 30);
            $employee->is_supervisor = true;
            $employee->save();
            $department = Department::firstWhere('id', 9);
            $department->manager_id = $employee->id;
            $department->save();
            Artisan::call('optimize:clear');
            $this->line('<fg=green>Manager assigned successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Manager assignment failed.');
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
