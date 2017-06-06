<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateUsers;
use Dompdf\Exception;
use Validator;

class UserController extends Controller
{
    /**
     * @api {post} /api/updateuser Update users
     * @apiName UpdateUser
     * @apiGroup User
     * @apiDescription Forced update of user data in the database
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
    public function updateUsers(){
        try{
            dispatch(new UpdateUsers());

            return ['status'=>true];
        }catch (Exception $e){
            abort(400);
        }
    }
}
