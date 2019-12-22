<?php
/**
 * Copyright (c) Silver Bullet 2019.
 * Author: Burhan Tanis
 * Date: 22.12.2019
 */

namespace App\Valuation;


use GuzzleHttp\Client;

class Cazana implements ValuationInterface
{
    /**
     * Guzzle instance
     */
    private $http_client;


    /**
     * Obtained data from Cazana API
     */
    private $data = [];


    /**
     * Cazana constructor.
     */
    public function __construct()
    {
        $this->http_client = new Client();
    }

    public function getVehicleDetails($vrm, $mileage)
    {
        // TODO: Implement getVehicleDetails() method.
    }

    public function getValuations($vrm, $mileage)
    {
        // TODO: Implement getValuations() method.
    }

    public function connectToApi(array $credentials)
    {

        //credential parameters for Cazana API
        $apiKey    = $credentials['api_key'];
        $endPoint  = $credentials['endpoint'];
        $carRegNum = $credentials['car_registration_number'];
        //-------

        // full endpoint
        $fullSoruceUri = $endPoint . '/' . $carRegNum . '/';


        // get the data from Cazana API by given parameters.
        $result = $this->data = $this->http_client->request('GET', $fullSoruceUri, [

            'query' => [
                'api_key' => $apiKey,
            ]
        ]);

        $array_data = json_decode($result->getBody(), true);
        $this->data = $array_data['data'];
        return $this;
    }
}