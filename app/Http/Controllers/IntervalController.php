<?php

namespace App\Http\Controllers;

use Dompdf\Exception;
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
        try {

            $ch = curl_init();
            if (FALSE === $ch)
                throw new Exception('failed to initialize');
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $jsonResponse = curl_exec($ch);

            if (FALSE === $jsonResponse )
                throw new Exception(curl_error($ch), curl_errno($ch));
            $response = json_decode($jsonResponse);
            return $response;
        }catch(Exception $e){
            print_r($e->getMessage());die;
        }
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

        if($response->code > 230){
            return collect($response);
        }
        $clients = collect($response->client);

        $clients = $clients->map(function ($item){
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
     * @param null $localId
     * @return mixed|static
     */
    public function getPersone($localId = null)
    {
        $request = $this->getDataRequest($localId);
        $urlRequest='/person/?'.$request;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        if($response->code > 230){
            return collect($response);
        }

        $persons = collect($response->person);

        $persons = $persons->map(function ($item){
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

        if($response->code > 230){
            return '';
        }
        return isset($response->personcontact['0']->value)?$response->personcontact['0']->value:'';
    }

    public function getGroup()
    {
        $urlRequest='/group/';

        $response = $this->requestInterval($urlRequest, $this->adminData);

        if($response->code > 230){
            return '';
        }
        dd($response);
    }

    /**
     * @param $dateBegin
     * @param $dateEnd
     * @return Collection|static
     */
    public function getTime($dateBegin, $dateEnd)
    {
        $urlRequest='/time/?limit=10000&billable=1&datebegin='.$dateBegin.'&dateend='.$dateEnd;

        $response = $this->requestInterval($urlRequest, $this->adminData);

        if($response->code > 230){
            return collect($response);
        }

        $times = collect($response->time);

        $times->transform(function($item){
            $item->active = $item->active == "t";
            $item->billable = $item->billable == "t";
            $item->clientactive = $item->clientactive == "t";
            $item = collect($item);

            return $item->toarray();
        });

        return $times;
    }

}