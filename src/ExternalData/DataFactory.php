<?php

/*
 *
 *   It is typical Factory Design Pattern. A factory is an object that can creates object without use of the
 *   constructor. I can create external source factory with this beautiful technique in a convenient way.
 *   In the future we can have more external source, so this class is the one responsible to  instantiate them without
 *   complexity.
 *
 *
 *
 *   Usage:
 *
 *
 *       $properties = DataFactory::obtainData(PropertiesData::class)
 *                                 ->getJsonDataFromExternalSource()
 *                                 ->showData();
 */

namespace App\ExternalData;

class DataFactory
{
    /**
     * Obtain the data from external source via given component name or class name
     *
     * @param $class_name
     * @return ObtainDataInterface
     */
    public static function obtainData($class_name): ObtainDataInterface
    {

        if ($class_name === 'properties') {

            return new PropertiesData();
        } else {

            return new $class_name();
        }


    }
}