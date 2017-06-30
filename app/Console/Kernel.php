<?php

namespace App\Console;

use App\Jobs\SendReportsJob;
use App\Jobs\UpdateUsers;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Time;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $time_report = Time::find(1);
        $schedule->call(function(){dispatch(new SendReportsJob());})->cron("0 $time_report->hour * * $time_report->week" );
        $schedule->call(function(){dispatch(new UpdateUsers());})->weekly()->fridays();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
