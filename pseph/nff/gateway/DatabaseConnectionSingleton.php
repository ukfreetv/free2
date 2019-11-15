<?php
/**
 * Created by PhpStorm.
 * User: Briantist
 * Date: 27/07/2018
 * Time: 11:17
 */

namespace pseph\nff\gateway;


use mysqli;
use free2\siteConfig;

class DatabaseConnectionSingleton
{
    /*
     *   Implement the system-wide database connection as a singleton pattern
     */
    private static $_instance; //The single instance
    private $_connection;

    private function __construct()
    {
        $this->_connection = new mysqli(siteConfig::MYSQLSERVER, siteConfig::MYSQLUSER, siteConfig::MYSQLPASSWORD, siteConfig::MYSQLSCHEMA);
        $this->_connection->options(MYSQLI_CLIENT_COMPRESS, true);

        $this->_connection->query("set names 'UTF8'");

        /*
         *
         *    You can't set the timezone is the command doesn't have a single query to itself!
         *
         */

        $this->_connection->query("set time_zone='Europe/London';");


        // Error handling
        if ($this->_connection->connect_error) {
            trigger_error("Connection Error: " . $this->_connection->connect_error, E_USER_ERROR);
        }
    }

    /*
	Get an instance of the Database
	@return Instance
	*/
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Magic method clone is empty to prevent duplication of connection
    public function getConnection()
    {
        return $this->_connection;
    }

    // Get mysqli connection
    private function __clone()
    {
    }
}