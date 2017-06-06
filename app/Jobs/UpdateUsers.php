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
        $iterval = new IntervalController();
        $get_users = $iterval->getPersone();

        if(!isset($get_users->code)){
            $user = new User();
            $all_users = User::all();

            $new_users = [];
            foreach ($get_users as $get_user)
            {
                $user_old = $all_users->where('interval_id', $get_user['interval_id']);

                if($user_old->count()>0)
                {
                    $user_old = $user_old->first();

                    if($user_old->interval_active != $get_user['interval_active']
                        || $user_old->interval_groupid != $get_user['interval_groupid'])
                    {
                        User::where('interval_id', $get_user['interval_id'])
                            ->update(['interval_active' => $get_user['interval_active'],
                                'email' => $iterval->getPersoneEmail($get_user['interval_id']),
                                'interval_groupid' => $get_user['interval_groupid'],
                                'interval_group' => $get_user['interval_group']]);
                    }
                }elseif($get_user['interval_active'])
                {
                    $new_users[]=['interval_id' => $get_user['interval_id'],
                        'interval_localid' => $get_user['interval_localid'],
                        'username' => $get_user['interval_username'],
                        'email' => $iterval->getPersoneEmail($get_user['interval_id']),
                        'firstname' => $get_user['interval_firstname'],
                        'lastname' => $get_user['interval_lastname'],
                        'interval_groupid' => $get_user['interval_groupid'],
                        'interval_group' => $get_user['interval_group'],
                        'interval_active' => $get_user['interval_active']
                    ];
                }
            }

            if(isset($new_users)){
                $user->insert($new_users);
            }
        }

    }
}
