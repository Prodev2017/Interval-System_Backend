<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\ReportController;
use App\Week;
use App\Approval;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendReports;

class SendReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        $managers = new ReportController();
        $managers = $managers->timeDataManagers();

        $week = new Week();

        $week->week_date_start =date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-6, date("Y")));
        $week->week_date_end =date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d"), date("Y")));

        $week->save();

        foreach ($managers as $manager){
            if(count($manager->timereport)){
                $timereports = $manager->timereport;
                foreach ($timereports as $key=>$timereport){
                    $approval = new Approval();

                    $timings = $timereport['data'];
                    $user_time = 0;
                    foreach ($timings as $timing){
                        $user_time += $timing['time'];
                    }

                    $approval->manager_id = $manager->interval_id;
                    $approval->user_id = $timereport['user_id'];
                    $approval->week_id = $week->id;
                    $approval->client_id = $timereport['client_id'];
                    $approval->status = false;
                    $approval->time = $user_time;

                    $approval->save();
                    $timereports[$key]['approval_id'] =$approval->id;
                }
                $manager->timereport = $timereports;
                $manager['week_id'] = $week->id;

                Mail::send(new SendReports($manager));
            }
        }
    }
}
