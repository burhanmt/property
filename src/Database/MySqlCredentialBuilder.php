<?php
/*
 *
 *   It is typical Builder Design Pattern with Fluent Interface (AKA Method Chaining) technique.
 *   It allows you to inject database credentials to "MySql.php" utility class
 *   in a comfort way. If you don't set any value of the credential items, it will use default values which are in
 *   "sr/Settings.php"
 *
 *   Usage:
 *                $credentials=  (new MySqlCredentialBuilder())->setHost('127.0.0.1')
 *                                                           ->setDbName('sample_db_name')
 *                                                           ->setUsername('burhan');
 *               $my_db = new MySql($credentials);
 *
 *  //I did not add password intetionally to the chain. Because all chains methods
 * //are optional if you don't use password, it will use automatically default value of it from "src/Settings.php"
 *
 *
 *
 *            or if you want to use default values which are in "src/Settings.php":
 *
 *
 *                       $my_db = new  MySql (new MySqlCredentialBuilder());
 */

namespace App\Database;


use App\Settings;

class MySqlCredentialBuilder
{

    /**
     * Database credentials
     * When I instantiate the class, No one should access these properties.
     */
    private $host;
    private $db_name;
    private $username;
    private $password;

    /**
     *
     * MySqlCredentialBuilder constructor.
     */
    public function __construct()
    {

        /*
         * If you don't one of the credential, the class will use default value from "Settings.php"
         */
        $this->host     = Settings::HOST;
        $this->db_name  = Settings::DB_NAME;
        $this->username = Settings::USERNAME;
        $this->password = Settings::PASSWORD;
    }


    public function setHost(string $host): MySqlCredentialBuilder
    {
        $this->host = $host;

        return $this;
    }

    public function setDbName(string $db_name): MySqlCredentialBuilder
    {
        $this->db_name = $db_name;

        return $this;

    }

    public function setUsername(string $username): MySqlCredentialBuilder
    {
        $this->username = $username;

        return $this;

    }


    public function setPassword(string $password): MySqlCredentialBuilder
    {
        $this->password = $password;

        return $this;

    }


    /*
     * This magic method is very important to make private properties of the class accessible in a subclass only. After setting the properties,
     * you can reach them with this technique.
     */

    public function __get($name)
    {
        // name property is used intentionally with "$" sign. Because of Magic Method  "__get".
        return $this->$name;
    }
}
