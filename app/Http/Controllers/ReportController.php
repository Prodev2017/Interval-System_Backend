<?php

namespace App\Http\Controllers;

use App\Approval;
use App\Week;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function pdfview($admin_id, $startdate = null, $enddate = null)
    {
        $out = $this->timeData();

        view()->share('items',$out);
        $pdf = PDF::loadView('pdfview');

        return $pdf->download('pdfview.pdf');
    }

    public function timeData($startdate = null, $enddate = null){
        $week = time() - (7 * 24 * 60 * 60);
        $startdate = isset($startdate)?$startdate:date('Y-m-d', $week);
        $enddate = isset($enddate)?$enddate:date('Y-m-d');

        $interval =new IntervalController();
        $alltimes = $interval->getTime($startdate, $enddate);
        $client = collect($interval->getClient());

        $timeform = [];

        foreach ($alltimes as $key=>$alltime)
        {
            $clientlocalid = $client->where('interval_id', $alltime['clientid'])->first();
            $alltime['clientlocalid'] = $clientlocalid['interval_localid'];

            $timeform[$alltime['personid']]['username'] =$alltime['person'];
            $timeform[$alltime['personid']]['data'][] =$alltime;
        }

        $out['data']=$timeform;
        $out['through']=date('m/d/Y');
        $out['from']=date('m/d/Y', $week);

        return $out;
    }

    public function timeDataAdminAll($admin_id, $startdate = null, $enddate = null){

        $data = $out = $this->timeData($startdate, $enddate);

        $admins = User::where('interval_group','Administrator')->orWhere('interval_groupid', 2)->approvals()->get();



    }

    public function approve($adminid, $userid, $weekid)
    {
        Approval::where('admin_id', $adminid)->where('user_id', $userid)->where('week_id',$weekid)->update(['status' => true]);

        return true;
    }

    public function approveAll($adminid, $weekid)
    {
        Approval::where('admin_id', $adminid)->where('week_id',$weekid)->update(['status' => true]);

        return true;
    }

    public function reportsStatus($weekid){
        //$reports = new Approval();

        $reports = Week::where('id','<=', $weekid)->where('id', '>', $weekid-4)->get();
        $reports = $reports->approvals->users;

        return $reports;
    }
}
