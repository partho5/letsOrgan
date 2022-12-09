<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FileUploadedMailingQueue extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $uploadInfo;

    public function __construct($uploadInfo)
    {
        $this->uploadInfo = $uploadInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sendingDate = Carbon::now()->format('F j');
        return $this->from("choriyedao@gmail.com", "LetsOrgan")
            ->subject("New File Uploaded [ $sendingDate ]")
            ->view('mailPages.file-uploaded');
    }
}
