<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateUsers;
use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\IntervalController;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function updateUsers(){
        dispatch(new UpdateUsers());
    }
}
