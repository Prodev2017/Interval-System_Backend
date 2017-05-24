<?php

namespace App\Jobs;

use App\Http\Controllers\IntervalController;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendReports;

class UpdateUsers implements ShouldQueue
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
        //
        $iterval = new IntervalController();
        $new_users = $iterval->getPersone();

        foreach ($new_users as $new_user){
            $user = User::updateOrCreate(
                ['interval_id' => $new_user['interval_id']],
                ['interval_localid'=>   $new_user['interval_localid'],
                    'username'=>        $new_user['interval_username'],
                    'email'=>           $iterval->getPersoneEmail($new_user['interval_id']),
                    'firstname'=>       $new_user['interval_firstname'],
                    'lastname'=>        $new_user['interval_lastname'],
                    'interval_groupid'=>$new_user['interval_groupid'],
                    'interval_group'=>  $new_user['interval_group'],
                    'interval_active'=> $new_user['interval_active']
                ]);
        }
    }
}
