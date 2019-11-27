<?php
/*
 *
 *    Properties data component. It is sealed. You can not inherit or change the properties of this class.
 *    You can use it with a factory builder. Like that:
 *
 *       $properties = DataFactory::obtainData(PropertiesData::class)
 *                                 ->getJsonDataFromExternalSource()
 *                                 ->showData();
 */

namespace App\ExternalData;

use GuzzleHttp\Client;

final class PropertiesData implements ObtainDataInterface
{
    const VERSION               = 1.0;
    const SOURCE_URL            = 'http://trialapi.craig.mtcdevserver.com/api/properties';
    const API_KEY               = '3NLTTNlXsi6rBWl7nYGluOdkl2htFHug';
    const PROPERTY_TABLE_SCHEMA = '     uuid VARCHAR(60) NOT NULL PRIMARY KEY,
                                        county VARCHAR(50),
                                        country VARCHAR(50),
                                        town VARCHAR(50),
                                        description TEXT,
                                        image_full VARCHAR(255),
                                        image_thumbnail VARCHAR(255),
                                        latitude DOUBLE,
                                        longitude DOUBLE,
                                        num_bedrooms INT UNSIGNED,
                                        num_bathrooms INT UNSIGNED,  
                                        price INT UNSIGNED,
                                        type VARCHAR(10),  
                                        source INT UNSIGNED DEFAULT 1,  
                                        created_at DATETIME,                                  
                                        updated_at DATETIME';

    const PROPERTY_TABLE_COLUMNS  = 'uuid, county, country, town, description, image_full, image_thumbnail, latitude, longitude, num_bedrooms, num_bathrooms, price, type, created_at, updated_at';
    const PROPERTY_TABLE_COLUMNS2 = ':uuid, :county, :country, :town, :description, :image_full, :image_thumbnail, :latitude, :longitude, :num_bedrooms, :num_bathrooms, :price, :type, :created_at, :updated_at';

    const TABLE_NAME = 'properties';


    /**
     * Guzzle instance
     */
    private $http_client;

    /**
     * Obtained data from external source
     */
    private $data = [];

    /**
     * Database connection instance
     */
    private $db_connection;


    /**
     * PropertiesData constructor.
     */
    public function __construct()
    {
        $this->http_client = new Client();
    }

    /**
     * @return PropertiesData
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getJsonDataFromExternalSource(): PropertiesData
    {


        $result = $this->data = $this->http_client->request('GET', self::SOURCE_URL, [

            'query' => [
                'api_key'      => self::API_KEY,
                'page[number]' => 1,
                'page[size]'   => 30
            ]
        ]);

        $array_data = json_decode($result->getBody(), true);
        $this->data = $array_data['data'];
        return $this;
    }


    /**
     *  I used Dependecy Injection. "$db_driver" is an object injection to the method.
     *
     * @param $db_driver
     * @return PropertiesData
     */
    public function saveDataToDatabase($db_driver): PropertiesData
    {
        $this->db_connection = $db_driver;

        $this->db_connection->createTableWithColumns(self::TABLE_NAME, self::PROPERTY_TABLE_SCHEMA);


        $properties = $this->data;


        // prepare sql and bind parameters
        // This statement is checking the records which recorded to the database. If they are already in client's
        // database, do not add once again.
        $stmt = $this->db_connection->getConnection()
                                    ->prepare('INSERT INTO ' . self::TABLE_NAME .
                                        ' (' . self::PROPERTY_TABLE_COLUMNS . ') VALUES (' . self::PROPERTY_TABLE_COLUMNS2 .
                                        ') ON DUPLICATE KEY UPDATE uuid=:uuid2');


        // This foreach code block  updates the details in the database if any changes are made to the details
        // of the property in the API
        foreach ($properties as $property) {

            // mysql search query
            $searchQuery = 'SELECT uuid,updated_at FROM ' . self::TABLE_NAME . ' WHERE (uuid = :uuid and updated_at <> :updated_at)';

            $searchResult = $this->db_connection->getConnection()
                                                ->prepare($searchQuery);


            //set your id to the query id
            $searchResult->bindParam(':uuid', $property['uuid']);
            $searchResult->bindParam(':updated_at', $property['updated_at']);
            $isUpdate = $searchResult->execute();

            if ($isUpdate) {

                $stmt->bindParam(':uuid', $property['uuid']);
                $stmt->bindParam(':uuid2', $property['uuid']);
                $stmt->bindParam(':county', $property['county']);
                $stmt->bindParam(':country', $property['country']);
                $stmt->bindParam(':town', $property['town']);
                $stmt->bindParam(':description', $property['description']);
                $stmt->bindParam(':image_full', $property['image_full']);
                $stmt->bindParam(':image_thumbnail', $property['image_thumbnail']);
                $stmt->bindParam(':latitude', $property['latitude']);
                $stmt->bindParam(':longitude', $property['longitude']);
                $stmt->bindParam(':num_bedrooms', $property['num_bedrooms']);
                $stmt->bindParam(':num_bathrooms', $property['num_bathrooms']);
                $stmt->bindParam(':price', $property['price']);
                $stmt->bindParam(':type', $property['type']);
                $stmt->bindParam(':created_at', $property['created_at']);
                $stmt->bindParam(':updated_at', $property['updated_at']);
                $stmt->execute();
            }
        }


        return $this;
    }

    /**
     * @return array
     */
    public function showData(): array
    {

        return $this->data;
    }
}