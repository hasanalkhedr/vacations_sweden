<?php

namespace App\Jobs\LeaveJobs;

use App\Mail\LeaveMails\SendLeaveRequestCanceledEmail;
use App\Mail\LeaveMails\SendLeaveRequestRejectedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLeaveRequestCanceledEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employee_email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employee_email)
    {
        $this->employee_email= $employee_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendLeaveRequestCanceledEmail();
        Mail::to($this->employee_email)->send($email);
    }
}
