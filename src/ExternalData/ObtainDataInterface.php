<?php

namespace ExternalData;

/*
 * If you want to create another external source component, just use this interface to implement it.
 */

interface ObtainDataInterface
{
    public function getJsonDataFromExternalSource(): PropertiesData;

    public function saveDataToDatabase($db_driver): PropertiesData;

    public function showData(): array;

}