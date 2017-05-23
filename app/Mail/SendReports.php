<?php

namespace App\Mail;

use App\Http\Controllers\ReportController;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReports extends Mailable
{
    use Queueable, SerializesModels;

    private $admin_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
//        $this->admin_id=$admin_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = new ReportController();
        $data = $data->timeData();


        return $this->from('example@example.com')
                    ->to('aaaa@aaaa.aa')
                    ->with(['items'=>$data])
                    ->view('mailreport');
    }
}
