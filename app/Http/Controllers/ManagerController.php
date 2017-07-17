<?php

namespace App\Http\Controllers;

use App\Selected;
use App\User;
use Dompdf\Exception;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * @api {post} /api/getselected Get selected users
     * @apiName GetSelectedUsers
     * @apiGroup Selected
     * @apiDescription Get a link between managers and users
     *
     * @apiSuccess {array}   managers                    All managers with users.
     * @apiSuccess {integer} managers.interval_id        The manager id from interval.
     * @apiSuccess {string}  managers.firstname          Firstname manager.
     * @apiSuccess {string}  managers.lastname           Lastname manager.
     * @apiSuccess {array}   managers.users              All users of manager.
     * @apiSuccess {string}  managers.users.firstname    Firstname user.
     * @apiSuccess {string}  managers.users.lastname     Lastname user.
     * @apiSuccess {integer} managers.users.user_id      The user id from interval.
     * @apiSuccess {integer} managers.users.manager_id   The id of users manager.
     * @apiSuccess {array}   available_users             All available users.
     * @apiSuccess {integer} available_users.interval_id The user id from interval.
     * @apiSuccess {string}  available_users.firstname   Firstname user.
     * @apiSuccess {string}  available_users.lastname    Lastname user.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "managers":
     *          [
     *              {
     *                  "interval_id":16064,
     *                  "firstname":"Chuck",
     *                  "lastname":"Beck",
     *                  "users": []   //manager don`t have users
     *              },
     *              ... ,
     *              {
     *                  "interval_id":252972,
     *                  "firstname":"Valerie",
     *                  "lastname":"Cooper",
     *                  "users":     // manager have users
     *                      [
     *                          {
     *                              "firstname":"Zhenghao",
     *                              "lastname":"Yang",
     *                              "user_id":289235,
     *                              "manager_id":252972
     *                          },
     *                          ...
     *                      ]
     *              }
     *          ],
     *       "available_users":
     *          [
     *              {
     *                  "interval_id":203991,
     *                  "firstname":"Allen",
     *                  "lastname":"Lyons"
     *              },
     *              {
     *                  "interval_id":279469,
     *                  "firstname":"Andrew",
     *                  "lastname":"Gannon"
     *              },
     *              ...
     *          ]
     *     }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     */
    public function getSelectedUsers(){
        try{
            $managers = User::where('interval_group','Manager')
                ->orWhere('interval_groupid',3)
                ->orWhere('interval_group','Administrator')
                ->orWhere('interval_groupid',2)
                ->select('interval_id', 'firstname', 'lastname')
                ->get();

            $selecteds = Selected::leftJoin('users', 'selecteds.user_id','=','users.interval_id')
                ->where('users.interval_group','=','Resource')
                ->orWhere('users.interval_groupid','=','4')
                ->select('users.firstname', 'users.lastname', 'selecteds.user_id', 'selecteds.manager_id')
                ->get();

            foreach ($managers as $key=>$manager){
                $managers[$key]->users = $selecteds->where('manager_id','=',$manager->interval_id)->toArray();
            }

            $user_id = $selecteds->map(function ($item){
                return $item->user_id;
            })->toArray();

            $available_users = User::whereNotIn('interval_id', $user_id)
                ->Where('interval_groupid','=','4')
                ->select('interval_id', 'firstname', 'lastname')
                ->get()->toArray();

            $out = collect();
            $out['managers'] = $managers->toArray();
            $out['available_users'] = $available_users;

            return $out;
        }catch (Exception $exception){
            abort(400);
        }
    }

    /**
     * @api {post} /api/setselected Set selected users
     * @apiName SetSelectedUsers
     * @apiGroup Selected
     * @apiDescription Write the relationship of users with managers
     *
     * @apiParam {array}    available_users     Array id of available users. Optional
     * @apiParam {array}    managers            Array all managers with users. Optional
     * @apiParam {integer}  managers.manager_id The manager id.
     * @apiParam {array}    managers.users      The users id.
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *       "available_users":
     *          {
     *              1,
     *              2,
     *             ...
     *              n
     *          },
     *       "managers":    //If the manager's settings are not changed, its data is no need to send0.
     *          [
     *              {
     *                  "manager_id":123,
     *                  "users":
     *                      {
     *                          1,
     *                          2,
     *                         ...
     *                          n
     *                      }
     *              }
     *          ]
     *     }
     *
     * @apiSuccess {boolean} status Successful request.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status":true
     *     }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     */
    public function setSelectedUsers(Request $request)
    {
        try{
            if(isset($request['available_users'])){
                Selected::whereIn('user_id', $request['available_users'])->delete();
            }

            if(isset($request['managers'])){
                $managers = $request['managers'];
                $selecteds = [];

                foreach ($managers as $manager){
                    if(isset($manager['users']) && isset($manager['manager_id'])){
                        $users = $manager['users'];

                        Selected::where('manager_id', $manager['manager_id'])->delete();

                        foreach ($users as $user){
                            $selecteds[] = [
                                'user_id'=> $user,
                                'manager_id' => $manager['manager_id']
                            ];
                        }
                    }
                }

                if(isset($selecteds)){
                    $select = new Selected();

                    $select->insert($selecteds);
                }
            }

            return ['status'=>true];
        } catch (Exception $e){
            abort(400);
        }
    }
}
