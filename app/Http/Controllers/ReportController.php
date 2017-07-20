<?php

namespace App\Http\Controllers;

use App\Jobs\SendReportsAdminJob;
use App\Mail\SendReportsAdmin;
use App\Time;
use App\Week;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\User;
use App\Approval;

class ReportController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     */
    /**
     * @api {get} /api/pdfview Download PDF report
     * @apiName GetPDFReport
     * @apiGroup Report
     *
     * @apiParam {Number} week_id week id.
     * @apiParam {Number} manager_id Interval manager id.
     *
     * @apiDescription Forms and downloads Time Sheet Reporting for the manager for the week in PDF format
     *
     * @apiSuccess {pdf_file} report-manager_id-week_id Time Sheet Reporting for the manager for the week in PDF format
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     */
    public function pdfview(Request $request)
    {
        try{
            $week = Week::find($request['week_id']);
            $outs = $this->timeReportManager($request['manager_id'], $week->week_date_start, $week->week_date_end)->timereport;
            foreach ($outs as $key=>$out_local){
                $approval = Approval::where('manager_id',$request['manager_id'])
                    ->where('user_id',$out_local['data']['0']['personid'])
                    ->where('week_id',$week->id)
                    ->first();
                $outs[$key]['approval_id']=$approval->id;
            }

            $out['data']=$outs;
            $out['through']=$week->week_date_end;
            $out['from']=$week->week_date_start;
            $out['week_id']=$request['week_id'];
            $out['interval_id']=$request['manager_id'];

            view()->share('items',$out);
            $pdf = PDF::loadView('pdfview');

            return $pdf->download('report-'.$request['manager_id'].'-'.$request['week_id'].'.pdf');
        }catch (Exception $e){
            abort(400);
        }
    }

    /**
     * @param null $startdate
     * @param null $enddate
     * @return mixed
     */
    public function timeData($startdate = null, $enddate = null){
        $startdate = isset($startdate)?$startdate:date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-6, date("Y")));
        $enddate = isset($enddate)?$enddate:date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d"), date("Y")));

        $interval =new IntervalController();
        $alltimes = $interval->getTime($startdate, $enddate);
        $client = collect($interval->getClient());
        $timeform = [];

        foreach ($alltimes as $key=>$alltime)
        {
                $clientlocalid = $client->where('interval_id', $alltime['clientid'])->first();
                $alltime['clientlocalid'] = $clientlocalid['interval_localid'];

                $timeform[$alltime['personid']]['username'] =$alltime['person'];
                $timeform[$alltime['personid']]['user_id'] =$alltime['personid'];
                $timeform[$alltime['personid']]['client_id'] =$alltime['clientid'];
                $timeform[$alltime['personid']]['data'][] =$alltime;
        }

        $out['data']=$timeform;
        $out['through']=$enddate;
        $out['from']=$startdate;

        return $out;
    }

    /**
     * @param null $startdate
     * @param null $enddate
     * @return mixed
     */
    public function timeDataManagers($startdate = null, $enddate = null){
        $data = $out = $this->timeData($startdate, $enddate);
        $managers = User::where('interval_group','Manager')
            ->orWhere('interval_groupid', 3)
            ->orWhere('interval_group','Administrator')
            ->orWhere('interval_groupid', 2)
            ->get();

        foreach ($managers as $key=>$manager){
            $users = $manager->selected;
            $timereport = [];

            foreach ($users as $user){
                if (isset($data['data'][$user['user_id']])){
                    $timereport[] =$data['data'][$user['user_id']];
                }
            }

            $managers[$key]->timereport =$timereport;
        }

        return $managers;
    }

    /**
     * @param $manager_id
     * @param null $startdate
     * @param null $enddate
     * @return mixed
     */
    public function timeReportManager($manager_id, $startdate = null, $enddate = null){
        $managers_data = $this->timeDataManagers( $startdate, $enddate);

        return $managers_data->where('interval_id', $manager_id)->first();
    }

    /**
     * @api {get} /api/approve Approval report
     * @apiName ApproveReportUser
     * @apiGroup Report
     *
     * @apiParam {Number} approvals_id approval id.
     * @apiParam {Number} manager_id Interval managet id.
     *
     * @apiDescription User report approval for the week. For approval from a letter or PDF report. Successful request - closes the tab. Error - "TimeSheets not found".
     */
    public function approve(Request $request)
    {
        try{
            $approve =Approval::where('id', $request['approvals_id'])
                ->where('manager_id', $request['manager_id']);

            $approve->update(['status' => true]);

            $approve_data = $approve->first();

            if(count($approve_data)){
                $user = User::where('interval_id',$approve_data->user_id)->first();
                $manager = User::where('interval_id',$approve_data->manager_id)->first();
                $week = Week::find($approve_data->week_id);

                $out=collect();
                $out->manager = $manager->firstname.' '.$manager->lastname;
                $out->users = [
                    ['name' => $user->firstname.' '.$user->lastname,
                    'hours' => $approve_data->time,
                    'approved' => $approve_data->updated_at]
                ];
                $out->datafrom = $week->week_date_start;
                $out->through = $week->week_date_end;

                $this->sendReportAdmin($out);
            } else{
                return "TimeSheets not found";
            }

            return '<script>window.close();</script>';
        } catch (Exception $exception){
            return "TimeSheets not found";
        }

    }

    /**
     * @api {get} /api/approveall Approval all reports
     *
     * @apiName ApproveReportAllUsers
     * @apiGroup Report
     *
     * @apiParam {Number} week_id week id.
     * @apiParam {Number} manager_id Interval managet id.
     *
     * @apiDescription A pproval all users report for the week. For approval from a letter or PDF report. Successful request - closes the tab. Error - "TimeSheets not found".
     */
    public function approveAll(Request $request)
    {
        try{
            $approve =Approval::where('manager_id', $request['manager_id'])
                ->where('week_id',$request['week_id']);

            $approve->update(['status' => true]);

            $users_id = $approve->pluck('user_id')
                ->toArray();
            $manager_id=$approve->pluck('manager_id')->first();

            $approve_data = $approve->first();

            if(count($approve_data)){
                $manager = User::where('interval_id',$manager_id)->first();
                $users = User::whereIn('interval_id',$users_id)->get();
                $week = Week::find($approve_data->week_id);

                $users_data =[];
                foreach ($users as $user){
                    $date_app = Approval::where('manager_id', $request['manager_id'])
                        ->where('week_id',$request['week_id'])->where('user_id',$user->interval_id)->first();
                    $users_data[] = ['name' => $user->firstname.' '.$user->lastname,
                                     'hours'=> $date_app->time,
                                     'approved'=> $date_app->updated_at];
                }

                $out=collect();
                $out->manager = $manager->firstname.' '.$manager->lastname;
                $out->users = $users_data;
                $out->datafrom = $week->week_date_start;
                $out->through = $week->week_date_end;

                $this->sendReportAdmin($out);
            }else{
                return "TimeSheets not found";
            }

            return '<script>window.close();</script>';
        }catch (Exception $e){
            return "TimeSheets not found";
        }
    }

    /**
     * @param $data
     */
    private function sendReportAdmin($data){
        $admins = User::where('interval_groupid', '2')
            ->orWhere('interval_group','Administrator')
            ->pluck('email')
            ->toArray();

        $data->email = $admins;

        Mail::send(new SendReportsAdmin($data));
    }

    /**
     * @api {post} /api/report Status of reports
     * @apiName GetReportsStatus
     * @apiGroup Report
     *
     * @apiParam {Number} startdate Date of first week. Date format ISO: YYYY-mm-dd. Optional
     * @apiParam {Number} client_id The client id of Interval. Optional
     *
     * @apiDescription Get status of reports for four weeks, starting from the specified week
     *
     * @apiSuccess {array}   approvals                 Reporting state data.
     * @apiSuccess {integer} approvals.approval_id     The Id of approve.
     * @apiSuccess {boolean} approvals.status          Status of report.
     * @apiSuccess {integer} approvals.user_id         User id of Interval.
     * @apiSuccess {string}  approvals.username        User name.
     * @apiSuccess {date}    approvals.week_date_end   Date of reports week end. Date format ISO: YYYY-mm-dd.
     * @apiSuccess {date}    approvals.week_date_start Date of reports week start. Date format ISO: YYYY-mm-dd.
     *
     * @apiSuccess {array}   client_id                  All clients data.
     * @apiSuccess {boolean} client_id.interval_active  Client status true/false.
     * @apiSuccess {string}  client_id.interval_id      The client id of Interval.
     * @apiSuccess {string}  client_id.interval_localid The client local id of Interval.
     * @apiSuccess {string}  client_id.interval_name    Name of client.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "approvals":
     *          [
     *              {
     *                  "approval_id":1,
     *                  "status":1,                     //status of report is approved
     *                  "user_id":242858,
     *                  "username":"William Lucas"
     *                  "week_date_end":"2017-05-30"
     *                  "week_date_start":"2017-05-24"
     *              },
     *              ...
     *          ],
     *      "client_id":
     *          [
     *              {
     *                  "interval_active":true,
     *                  "interval_id":"234510",
     *                  "interval_localid":"00032",
     *                  "interval_name":"ANZ - Australia and New Zealand"
     *              },
     *              ...
     *          ]
     *     }
     *
     * @apiErrorExample Error-Response:
     *      HTTP/1.1 400 Bad Request
     */
    public function reportsStatus(Request $request){
        try {
            $startdata = isset($request['startdate'])
                ? $request['startdate']
                : $this->getWeekNow(1);

            $enddata = isset($request['startdate'])
                ? date('Y-m-d', mktime(0, 0, 0, substr($request['startdate'], 5, 2), substr($request['startdate'], 8, 2)+28, substr($request['startdate'], 0, 4)))
                : $this->getWeekNow();

            $approvals = Week::leftJoin('approvals', 'weeks.id', '=', 'approvals.week_id')
                ->where('week_date_end', '>=', $startdata)
                ->where('week_date_end', '<=', $enddata)
                ->select('weeks.week_date_start', 'weeks.week_date_end', 'approvals.status', 'approvals.user_id', 'approvals.id as approval_id')
                ->get();

            if (isset($request['client_id'])) {
                $approvals = $approvals->where('client_id', '=', $request['client_id']);
            }

            $users = [];

            foreach ($approvals as $approval) {
                $users[] = $approval->user_id;
            }

            $users = User::whereIn('interval_id', $users)->get();

            foreach ($approvals as $key => $approval) {
                $user = $users->where('interval_id', $approval->user_id)->first();
                $approvals[$key]['username'] = $user['firstname'] . ' ' . $user['lastname'];
            }

            $clients = new IntervalController();

            $clients = $clients->getClient();

            $clients = $clients->where('interval_active', '=', '1');

            $out = collect();

            $out['approvals'] = $approvals;
            $out['clients'] = $clients;

            return $out;
        }catch (Exception $e){
            abort(400);
        }
    }

    private function getWeekNow($n = 0){
        for($i=0; $i<7;$i++){
            if (date('N',(time()-$i*24*60*60)) == 5){
               $day = $i;

               if($n == 0){
                    $date = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $day, date("Y")));
                }else{
                    $date = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - ($day+28), date("Y")));
                }

                return $date;
            }
        }
    }

    /**
     * @api {post} /api/sendreminders Send reminders
     * @apiName SendReminders
     * @apiGroup Report
     *
     * @apiParam {Array} approval_id The approval id for approve.
     *
     * @apiDescription Resend reports that have not been approved by the managers
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
    public function sendReminders()
    {
        try{
            dispatch(new SendReportsAdminJob());
            return ['status'=>true];
        }catch (Exception $e){
            abort(400);
        }
    }

    /**
     * @api {get} /api/timereport Get Time of reporting
     * @apiName Time of reporting
     * @apiGroup Report
     *
     * @apiDescription Get time of reporting
     *
     * @apiSuccess {integer}   week                 Week number.
     * @apiSuccess {integer}   hour                 Hour of the day.
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "week":6,
     *          "hour":22
     *      }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     */
    public function getTimeReport(){
        try{
            return Time::find(1);
        }catch (Exception $e){
            abort(400);
        }
    }

    /**
     * @api {post} /api/timereport Set Time of reporting
     * @apiName Set Time of reporting
     * @apiGroup Report
     *
     * @apiDescription Set time of reporting
     *
     * @apiParam {integer}   week                 Week number.
     * @apiParam {integer}   hour                 Hour of the day.
     *
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
    public function setTimeReport(Request $request){
        try{
            if(isset($request['week']) && isset($request['hour'])){
                $time = Time::find(1);

                $time->week = $request['week'];
                $time->hour = $request['hour'];

                $time->save();

                return ['status'=>true];
            } else
            {
                return ['status'=>false];
            }
        }catch (Exception $e){
            abort(400);
        }
    }
}
