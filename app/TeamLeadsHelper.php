<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 10.10.17
 * Time: 16:19
 */

namespace App;

use Illuminate\Database\Eloquent\Collection;

class TeamLeadsHelper
{
    protected static $tlList = null;

    /**
     * @return Collection
     */
    public static function getTeamLeadsList()
    {
        if (is_null(self::$tlList)) {
            $managers = json_decode(file_get_contents('../team_leads_frg.json'), true);
            $managerIds = array_pluck($managers, 'interval_id');
            $managers = User::whereIn('interval_id', $managerIds)->get();

            self::$tlList = $managers;

            // old way of gettin tl list
//            $managers = User::where('interval_group','Manager')
//                ->orWhere('interval_groupid', 3)
//                ->orWhere('interval_group','Administrator')
//                ->orWhere('interval_groupid', 2)
//                ->get();
        }

        return self::$tlList;
    }

    public static function getTeamLeadsListIntervalIds()
    {
        $tlList = self::getTeamLeadsList();

        if (empty($tlList)) {
            [];
        }

        return $tlList->pluck('interval_id');
    }
}