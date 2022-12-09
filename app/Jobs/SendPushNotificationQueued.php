<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPushNotificationQueued implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $devices, $title, $link;

    public function __construct($devices, $title, $link)
    {
        $this->devices = $devices;
        $this->title = $title;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->devices as $deviceId){
            \OneSignal::sendNotificationToUser("$this->title", $deviceId, $url = $this->link, $data = null, $buttons = null, $schedule = null);
        }
    }
}
