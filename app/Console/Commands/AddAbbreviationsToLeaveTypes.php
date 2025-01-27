<?php

namespace App\Console\Commands;

use App\Models\LeaveType;
use Illuminate\Console\Command;

class AddAbbreviationsToLeaveTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AddAbbreviationsToLeaveTypes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Annual Leave
        $leaveType = LeaveType::firstWhere('name', 'annual leave');
        $leaveType->abbreviation = 'CA';
        $leaveType->save();

        // Recovery
        $leaveType = LeaveType::firstWhere('name', 'recovery');
        $leaveType->abbreviation = 'R';
        $leaveType->save();

        // Assignment
        $leaveType = LeaveType::firstWhere('name', 'assignment');
        $leaveType->abbreviation = 'M';
        $leaveType->save();

        // Training
        $leaveType = LeaveType::firstWhere('name', 'training');
        $leaveType->abbreviation = 'F';
        $leaveType->save();

        // Sick Leave
        $leaveType = LeaveType::firstWhere('name', 'sick leave');
        $leaveType->abbreviation = 'AM';
        $leaveType->save();

        // Bereavement leave
        $leaveType = LeaveType::firstWhere('name', 'bereavement leave');
        $leaveType->abbreviation = 'CD';
        $leaveType->save();

        // Maternity leave
        $leaveType = LeaveType::firstWhere('name', 'maternity leave');
        $leaveType->abbreviation = 'CM';
        $leaveType->save();

        // Paternity leave
        $leaveType = LeaveType::firstWhere('name', 'paternity leave');
        $leaveType->abbreviation = 'CP';
        $leaveType->save();

        // Marriage leave
        $leaveType = LeaveType::firstWhere('name', 'marriage leave');
        $leaveType->abbreviation = 'MAR';
        $leaveType->save();

        // Remote Work
        $leaveType = LeaveType::firstWhere('name', 'remote work');
        $leaveType->abbreviation = 'RW';
        $leaveType->save();
        return Command::SUCCESS;
    }
}
