<?php

use Database\MySql;
use Database\MySqlCredentialBuilder;
use ExternalData\DataFactory;
use ExternalData\PropertiesData;


class Properties
{

    /**
     * Populate the database with properties data via "PropertiesData::class"(it is external api connector) component.
     *
     * @return array
     */
    public static function populateDatabaseFromApi(): array
    {

        $my_db = new  MySql (new MySqlCredentialBuilder());

        $properties = DataFactory::obtainData(PropertiesData::class)
                                 ->getJsonDataFromExternalSource()
                                 ->saveDataToDatabase($my_db)
                                 ->showData();


        return $properties;


    }


    /**
     *  Show all properties records from database
     *
     * @return array
     */
    public static function showPropertiesFromDatabase(): array
    {

        $my_db = new  MySql (new MySqlCredentialBuilder());

        $getProperties = $my_db->getConnection()
                               ->query('SELECT * from properties');
        return $getProperties->fetchAll();
    }
}
