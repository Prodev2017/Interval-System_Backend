<?php

namespace App\Http\Controllers;

class IntervalController extends Controller
{
    private $adminData;
    private $userData;
    private $baseUrl = 'https://api.myintervals.com';

    public function __construct(){
        $this->adminData = array("key" => env('INTERVALS_ACCESS_TOKEN'), "password" => env('INTERVALS_PASSWORD'));
        $this->userData = array("key" => env('INTERVALS_ACCESS_TOKEN'), "password" => env('INTERVALS_PASSWORD')); //Change to auth user data
    }

    private function requestInterval($urlRequest, $userData)
    {
        $credentials = $userData['key'].':'.$userData['password'];
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

    private function getDataRequest($id = null, $limit=1000)
    {
        if (isset($id)){
           return $request = 'localid='.$id;
        }

        return $request = 'limit='.$limit;
    }

    public function getClient($localId = null)
    {
        $request = $this->getDataRequest($localId);

        $urlRequest='/client/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        return isset($localId)?$response->client['0']:$response->client;
    }

    public function getMe()
    {
        $urlRequest='/me/';

        $response = $this->requestInterval($urlRequest, $this->userData);

        return $response->me['0'];
    }

    public function getPersone($localId = null)
    {
        $request = $this->getDataRequest($localId);

        $urlRequest='/person/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        return isset($localId)?$response->person['0']:$response->person;
    }

    public function getPersoneEmail($id)
    {
        $urlRequest='/personcontact/?contacttypeid=3&personid='.$id;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        return $response->personcontact['0']->value;
    }

    public function getProject($localId = null)
    {
        $request = $this->getDataRequest($localId);

        $urlRequest='/project/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        return isset($localId)?$response->project['0']:$response->project;
    }

    public function getProjectModule($projectId, $personId=null)
    {
        $urlRequest='/projectmodule/?limit=1000&projectid='.$projectId.(isset($personId)?'&personid='.$personId:'');

        $response = $this->requestInterval($urlRequest, $this->adminData);

        return $response->projectmodule;
    }

    public function getTask($localId=null)
    {
        $request = $this->getDataRequest($localId);

        $urlRequest='/task/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        return isset($localId)?$response->task['0']:$response->task;
    }

    public function getTime($dateBegin, $dateEnd)
    {
        $urlRequest='/time/?limit=1000&datebegin='.$dateBegin.'&dateend='.$dateEnd;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        return isset($localId)?$response->time['0']:$response->time;
    }

    public function getWorkType($localId=null)
    {
        $request = $this->getDataRequest($localId);

        $urlRequest='/worktype/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        return isset($localId)?$response->worktype['0']:$response->worktype;
    }
}