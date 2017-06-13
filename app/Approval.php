<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
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
