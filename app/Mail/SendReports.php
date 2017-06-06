<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReports extends Mailable
{
    use Queueable, SerializesModels;

    private $manager;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($manager)
    {

        $this->manager=$manager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_ADDRESS'))
                    ->to($this->manager->email)
                    ->with(['items'=>$this->manager])
                    ->view('mailreport');
    }
}
