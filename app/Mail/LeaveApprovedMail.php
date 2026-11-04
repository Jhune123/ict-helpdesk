<?php

namespace App\Mail;

use App\Models\LeaveApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LeaveApplication $leave) {}

    public function build(): self
    {
        $staffName = $this->leave->user->name;

        return $this->subject('ICT staff leave notice: '.$staffName)
            ->view('emails.leave-approved')
            ->with(['staffName' => $staffName]);
    }
}
