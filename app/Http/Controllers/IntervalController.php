<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

class IntervalController extends Controller
{
    private $adminData;
    private $baseUrl = 'https://api.myintervals.com';

    public function __construct(){
        $this->adminData = array("interval_token" => env('INTERVALS_ACCESS_TOKEN'), "password" => env('INTERVALS_PASSWORD'));
    }

    /**
     * @param $urlRequest
     * @param $userData
     * @return mixed
     */
    private function requestInterval($urlRequest, $userData)
    {
        $credentials = $userData['interval_token'].':'.$userData['password'];
        $url = $this->baseUrl.$urlRequest;

        $headers = array(
            "GET HTTP/1.0",
            "Content-type:  application/json;charset=\"utf-8\"",
            "Accept:  application/json",
            "Authorization: Basic " . base64_encode($credentials)
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $jsonResponse = curl_exec($ch);

        $response = json_decode($jsonResponse);

        return $response;
    }

    /**
     * @param null $id
     * @param int $limit
     * @return string
     */
    private function getDataRequest($id = null, $limit=1000)
    {
        if (isset($id)){
           return $request = 'localid='.$id;
        }

        return $request = 'limit='.$limit;
    }

    /**
     * @param null $localId
     * @return mixed|static
     */
    public function getClient($localId = null)
    {
        $request = $this->getDataRequest($localId);
        $urlRequest='/client/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        $clients = collect($response->client);

        $clients = $clients->map(function ($item, $key){
            $client = [
                'interval_id' => $item->id,
                'interval_name' => $item->name,
                'interval_active' => $item->active == "t",
                'interval_localid' =>$item->localid
            ];

            return $client;
        });

        return isset($localId)?$clients->first():$clients;
    }

    /**
     * @return mixed
     */
    public function getMe($user)
    {
        $urlRequest='/me/';
        $userData['interval_token'] = $user['interval_token'];
        $userData['password'] = $user['password'];

        $response = $this->requestInterval($urlRequest, $userData);

        $me = collect($response->me);

        $me = $me->map(function ($item, $key){
            $me_local = [
                'interval_id' => $item->id,
                'interval_localid' => $item->localid,
                'interval_firstname' => $item->firstname,
                'interval_username' => $item->username,
                'interval_lastname' =>$item->lastname,
                'interval_groupid' =>$item->groupid,
                'interval_group' =>$item->group
            ];

            return $me_local;
        });

        return $me->first();
    }

    /**
     * @param null $localId
     * @return mixed|static
     */
    public function getPersone($localId = null)
    {
        $request = $this->getDataRequest($localId);
        $urlRequest='/person/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        $persons = collect($response->person);

        $persons = $persons->map(function ($item, $key){
            $person = [
                'interval_id' => $item->id,
                'interval_localid' => $item->localid,
                'interval_firstname' => $item->firstname,
                'interval_username' => $item->username,
                'interval_lastname' =>$item->lastname,
                'interval_groupid' =>$item->groupid,
                'interval_active' =>$item->active == "t",
                'interval_group' =>$item->group
            ];

            return $person;
        });

        return isset($localId)?$persons->first():$persons;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPersoneEmail($id)
    {
        $urlRequest='/personcontact/?contacttypeid=3&personid='.$id;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        return $response->personcontact['0']->value;
    }

    /**
     * @param null $localId
     * @return mixed|static
     */
    public function getProject($localId = null)
    {
        $request = $this->getDataRequest($localId);
        $urlRequest='/project/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        $projects = collect($response->project);

        $projects = $projects->map(function ($item, $key){
            $project = [
                'interval_id' => $item->id,
                'interval_name' => $item->name,
                'interval_alert_percent' => $item->alert_percent,
                'interval_active' => $item->active == "t",
                'interval_client' =>$item->client,
                'interval_clientid' =>$item->clientid,
                'interval_localid' =>$item->localid,
                'interval_manager' =>$item->manager,
                'interval_managerid' =>$item->managerid
            ];

            return $project;
        });

        return isset($localId)?$projects->first():$projects;
    }

    /**
     * @param $projectId
     * @param null $personId
     * @return Collection|static
     */
    public function getProjectModule($projectId, $personId=null)
    {
        $urlRequest='/projectmodule/?limit=1000&projectid='.$projectId.(isset($personId)?'&personid='.$personId:'');

        $response = $this->requestInterval($urlRequest, $this->adminData);

        $projectmodules = collect($response->projectmodule);

        $projectmodules->transform(function($item, $key){
            $item->active = $item->active == "t";
            $item = collect($item);

            return $item->toarray();
        });

        return $projectmodules;
    }

    /**
     * @param null $localId
     * @return mixed|static
     */
    public function getTask($localId=null)
    {
        $request = $this->getDataRequest($localId);
        $urlRequest='/task/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        $tasks = collect($response->task);

        $tasks = $tasks->map(function ($item, $key){
            $task = collect($item);

            return $task->toArray();
        });

        return isset($localId)?$tasks->first():$tasks;
    }

    /**
     * @param $dateBegin
     * @param $dateEnd
     * @return Collection|static
     */
    public function getTime($dateBegin, $dateEnd)
    {
        $urlRequest='/time/?limit=10000&datebegin='.$dateBegin.'&dateend='.$dateEnd;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        $times = collect($response->time);

        $times->transform(function($item, $key){
            $item->active = $item->active == "t";
            $item->billable = $item->billable == "t";
            $item->clientactive = $item->clientactive == "t";
            $item = collect($item);

            return $item->toarray();
        });

        return $times;
    }

    /**
     * @return Collection|static
     */
    public function getWorkType()
    {
        $request = $this->getDataRequest();
        $urlRequest='/worktype/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        $worktyps = collect($response->worktype);

        $worktyps->transform(function($item, $key){
            $item->active = $item->active == "t";
            $item = collect($item);

            return $item->toarray();
        });

        return $worktyps;
    }
}