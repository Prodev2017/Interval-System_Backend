<?php

namespace App;

use App\Http\Controllers\IntervalController;
//use Illuminate\Notifications\Notifiable;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\Auth;

class User extends Model
{
//    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [
//        'password', 'remember_token',
//    ];


    public function approvals()
    {
        return $this->hasMany('App\Approval', 'manager_id', 'interval_id');
    }

    public function selected()
    {
        return $this->hasMany('App\Selected', 'manager_id', 'interval_id');
    }
}
