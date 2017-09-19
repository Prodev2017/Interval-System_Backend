<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Mail;
use App\Approval;
use App\Http\Controllers\ReportController;
use App\Mail\SendReports;


class SendReportsAdminJob implements ShouldQueue
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
        $approvals = Approval::LeftJoin('weeks', 'approvals.week_id', '=', 'weeks.id')
            ->where('approvals.status', false)
            ->select('approvals.week_id', 'weeks.week_date_start', 'weeks.week_date_end', 'approvals.manager_id', 'approvals.user_id', 'approvals.id as approval_id')
            ->get();

        $approval_unique = $approvals->unique('week_date_end', 'week_date_start');
        $reportConrtr = new ReportController();
        foreach ($approval_unique as $item) {
            $timeDataManagers = $reportConrtr->timeDataManagers($item->week_date_start, $item->week_date_end);
            $managers_id = $approvals->where('week_date_end', $item->week_date_end)
                ->where('week_date_start', $item->week_date_start)
                ->pluck('manager_id')
                ->toArray();

            $week_id = $approvals->where('week_date_end', $item->week_date_end)
                ->where('week_date_start', $item->week_date_start)
                ->pluck('week_id')
                ->first();
            $data_response = array();
            foreach ($managers_id as $manager_id) {
                $reports = $timeDataManagers->where('interval_id', $manager_id)->first();

                $timereports = $reports->timereport;

                foreach ($timereports as $key => $timereport) {
                    $approv = Approval::where('week_id', $week_id)
                        ->where('manager_id', $manager_id)
                        ->where('user_id', $timereport['user_id'])
                        ->whereNotIn('id',$data_response)
                        ->first();

                    if (count($approv)) {
                        $timereports[$key]['approval_id'] = $approv->id;
                    } else {
                        unset($timereports[$key]);
                    }
                }
                $reports->timereport = $timereports;
                $reports['week_id'] = $week_id;
                $data_response[] = $reports->id;
                Mail::send(new SendReports($reports));
            }
        }
//        return true;
    }
}
