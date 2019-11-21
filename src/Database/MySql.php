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

    private $conn;

    // The db connection is established in the private constructor.
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

    public function deleteData(string $table_name, string $id): bool
    {


        $stmt = $this->conn->prepare('DELETE FROM ' . $table_name . ' WHERE uuid =:id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        (!$stmt->rowCount()) ? $result = false : $result = true;

        return $result;
    }

}