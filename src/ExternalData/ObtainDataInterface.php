<?php

namespace ExternalData;

interface ObtainDataInterface
{
    public function getJsonDataFromExternalSource(): PropertiesData;

    public function saveDataToDatabase($db_driver): PropertiesData;

    public function showData(): array;

}