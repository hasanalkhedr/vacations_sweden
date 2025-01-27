<?php

namespace App\Console\Commands;

use App\Models\LeaveType;
use Illuminate\Console\Command;

class AddRemoteWorkLeaveType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AddRemoteWorkLeaveType';

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
        LeaveType::create(['name' => 'remote work', 'abbreviation' => 'TT']);
        return Command::SUCCESS;
    }
}
