<?php

namespace App\controller;

use App\Database\MySql;
use App\Database\MySqlCredentialBuilder;

class AdminController
{
    private $my_db;


    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->my_db = new  MySql (new MySqlCredentialBuilder());
    }

    /**
     *
     *  It records given array data to the database. If it fails, return value will be false otherwise true.
     *
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function storeJsonData($data): bool
    {


        $new_record = ['uuid'          => bin2hex(random_bytes(16)),
                       'country'       => $data['country'],
                       'county'        => $data['county'],
                       'town'          => $data['town'],
                       'description'   => $data['description'],
                       'latitude'      => 0.00,
                       'longitude'     => 0.00,
                       'num_bedrooms'  => $data['bedrooms'],
                       'num_bathrooms' => $data['bathrooms'],
                       'price'         => $data['price'],
                       'type'          => $data['propertyType'],
                       'source'        => 0,

        ];


        return $this->my_db->addData('properties', $new_record);


    }

    /**
     * Delete a record from database by given id number
     *
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {

        return $this->my_db->deleteData('properties', $id);
    }

    /**
     * Show a record from database by given id number
     *
     * @param string $id
     * @return array
     */
    public function getData(string $id): array
    {

        return $this->my_db->getData('properties', $id);
    }

    /**
     * Update a record from database by given id number
     *
     * @param array $values
     * @param string $id
     * @return bool
     */
    public function updateData(array $values, string $id): bool
    {
        return $this->my_db->updateData('properties', ['county','country','num_bedrooms','type'], $values, $id);
    }
}