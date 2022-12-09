<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExamPostMailingQueue extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $examInfo;
    public function __construct($examInfo)
    {
        $this->examInfo = $examInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $examDate = Carbon::parse($this->examInfo['examDate'])->format('j F');
        $courseName = $this->examInfo['course'];
        return $this->from('choriyedao@gmail.com', 'LetsOrgan')
            ->subject("$courseName Exam @ $examDate")
            ->view('mailPages.exam-post');
    }
}
