<?php
/**
 * Copyright (c) Silver Bullet 2019.
 * Author: Burhan Tanis
 * Date: 22.12.2019
 */


namespace App\Valuation;


interface ValuationInterface
{
    public function getVehicleDetails($vrm, $mileage);

    public function getValuations($vrm, $mileage);

    public function connectToApi(Array $credentials);
}