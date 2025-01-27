<?php

namespace App\Mail\LeaveMails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLeaveRequestIncomingEmailReplacement extends Mailable
{
    use Queueable, SerializesModels;

    public $fromDate, $toDate, $employee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fromDate, $toDate, $employee)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['fromDate'] = $this->fromDate;
        $data['toDate'] = $this->toDate;
        $data['employee'] = $this->employee;
        return $this->subject('Remplacement de demande de congé')
            ->view('emails.leaves.incoming-leave-request-replacement', $data);
    }
}
