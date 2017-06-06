<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Selected extends Model
{
    public function clearSelected($selected){
        $this->whereIn('user_id',$selected['users'])
            ->whereNot('manager_id',$selected['manager_id'])
            ->orWhere(function ($query) use ($selected){
                $query->where('manager_id', $selected['manager_id'])
                    ->whereNotIn('user_id', $selected['users']);
            })->delete;
    }
}
