<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignmentPostMailingQueue extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $assignmentInfo;
    public function __construct($assignmentInfo)
    {
        $this->assignmentInfo = $assignmentInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $deadline = Carbon::parse($this->assignmentInfo['deadline'])->format('j F');
        $courseName = $this->assignmentInfo['course'];
        return $this->from('choriyedao@gmail.com', 'LetsOrgan')
            ->subject("$courseName Assignment [ Deadline $deadline ]")
            ->view('mailPages.assignment-post');
    }
}
