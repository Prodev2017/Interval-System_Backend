<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    public function getReports($weekid){
        $reports = DB::table('approvals')
                    ->leftJoin('users', 'approvals.user_id','=','users.id')
                    ->leftJoin('weeks', 'approvals.week_id','=','week.id')
                    ->where('approvals.week_id','<=', $weekid)
                    ->where('approvals.week_id','>', $weekid-4)
                    ->select('approvals.*','users.username', 'weeks.week_date')
                    ->sortBy('weeks.week_date')
                    ->get();
    }

    public function weeks(){
        return $this->hasMany('Week');
    }

    public function user(){
        return $this->hasOne('User', 'interval_id','user_id');
    }

    public function admin(){
        return $this->hasOne('App/User','interval_id','manager_id');
    }
}
