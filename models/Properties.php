<?php

use Database\MySql;
use Database\MySqlCredentialBuilder;
use ExternalData\DataFactory;
use ExternalData\PropertiesData;


class Properties
{

    public static function populateDatabaseFromApi(): array
    {

        $my_db = new  MySql (new MySqlCredentialBuilder());

        $properties = DataFactory::obtainData(PropertiesData::class)
                                 ->getJsonDataFromExternalSource()
                                 ->saveDataToDatabase($my_db)
                                 ->showData();


        return $properties;


    }


    public static function showPropertiesFromDatabase(): array
    {

        $my_db = new  MySql (new MySqlCredentialBuilder());

        $getProperties = $my_db->getConnection()
                               ->query('SELECT * from properties');
        return $getProperties->fetchAll();
    }
}
