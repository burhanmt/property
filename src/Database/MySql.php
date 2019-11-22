<?php
/*
 *   MySql database utility class
 *
 */

namespace Database;

use PDO;
use PDOException;

class MySql
{

    /**
     * Database connection instance
     *
     */
    private $conn;



    /**
     * The db connection is established in the private constructor.
     * MySql constructor.
     * @param MySqlCredentialBuilder $builder
     */
    public function __construct(MySqlCredentialBuilder $builder)
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $dsn     = "mysql:host={$builder->host};dbname={$builder->db_name};charset=utf8mb4";
        try {
            $this->conn = new PDO($dsn, $builder->username, $builder->password, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

    }


    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * @param string $table_name
     * @param string $columns_schema
     * @return bool
     */
    public function createTableWithColumns(string $table_name, string $columns_schema): bool
    {
        $is_successful = true;
        try {
            $sql = 'CREATE TABLE ' . $table_name . ' (' . $columns_schema . ')';

            $this->conn->exec($sql);
        } catch (PDOException $e) {
            $is_successful = false;
        }

        return $is_successful;
    }

    public function closeConnection()
    {

        $this->conn->close();

    }

    /**
     * @param string $table_name
     * @param array $data
     * @return bool
     */
    public function addData(string $table_name, array $data): bool
    {

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table_name,
            implode(", ", array_keys($data)),
            ":" . implode(", :", array_keys($data))
        );

        $statement = $this->conn->prepare($sql);
        return $statement->execute($data);
    }

    /**
     * @param string $table_name
     * @param string $id
     * @return array
     */
    public function getData(string $table_name, string $id): array
    {


        $stmt = $this->conn->prepare('SELECT * FROM ' . $table_name . ' WHERE uuid =:id');
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $table_name
     * @param string $id
     * @return bool
     */
    public function deleteData(string $table_name, string $id): bool
    {


        $stmt = $this->conn->prepare('DELETE FROM ' . $table_name . ' WHERE uuid =:id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        (!$stmt->rowCount()) ? $result = false : $result = true;

        return $result;
    }

    /**
     * @param string $table_name
     * @param array $columns
     * @param array $values
     * @param string $id
     * @return bool
     */
    public function updateData(string $table_name, array $columns, array $values, string $id): bool
    {
        // initialize an array with values:
        $params = [];

        // initialize a string with `fieldname` = :placeholder pairs
        $setStr = "";

        // loop over source data array
        $i=0;
        foreach ($columns as $key) {
            if (isset($values)) {
                $setStr       .= "`$key` = :$key,";
                $params[$key] = $values[$i];
                $i++;
            }

        }
        $setStr = rtrim($setStr, ",");

        $params['id'] = $id;

        return $this->conn->prepare("UPDATE $table_name SET $setStr WHERE uuid = :id")
                          ->execute($params);
    }

}