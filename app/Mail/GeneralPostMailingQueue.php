<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralPostMailingQueue extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $post;
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sendingDate = Carbon::now()->format('j F ');
        return $this->from('choriyedao@gmail.com', 'LetsOrgan')
            ->subject("New General Post [ $sendingDate ]")
            ->view('mailPages.general-post');
    }
}
