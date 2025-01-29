<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class ChangeRoleDisplayName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-role-display-name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $role = Role::firstWhere('name', 'sg');
        // $role->display_name = 'PM';
        // $role->save();

        $role = Role::firstWhere('name', 'head');
        $role->display_name = 'Directice IF';
        $role->save();

        return Command::SUCCESS;
    }
}
