<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Approval;

class Week extends Model
{
    //
    public function approvals(){
        return $this->hasMany('Approval');
    }
}
