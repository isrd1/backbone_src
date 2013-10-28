<?php
/**
 * pdoDB
 * @filesource
 * @package classes
 * @author Rob Davis
 */
/**
 * require the application registry class
 */
require_once('applicationregistry.class.php');
/**
 *
 * Class for handling the database connection, uses the singleton pattern
 * @package classes
 * @example ../examples/StudentList-class.php
 */
Class pdoDB {
  /**
   *
   * @var PDO private static to hold the connection
   */
  private static $dbConnection = null;

  /**
  * make the next 2 functions private to prevent normal class instantiation
  */
  private function __construct() {}
  private function __clone() {}

  /**
   * Return DB connection or create initial connection.
   * Uses the dns, username and password values from ApplicationRegistry
   * {@source}
   * @return object (PDO)
   * @access public
   * @throws Exception
   */
  public static function getConnection() {
    // if there isn't a connection already then create one
    if ( !self::$dbConnection ) {
        try {
        	$dns = ApplicationRegistry::getDNS();
        	$username = ApplicationRegistry::getUsername();
        	$password = ApplicationRegistry::getPassword();
            self::$dbConnection = new PDO( $dns, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
            self::$dbConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
         }
         catch( PDOException $e ) {
            // in a production system you would log the error not display it
            throw new Exception("<div>{$e->getMessage()}</div>".'Failed to connect');
         }
    }
    // return the connection
    return self::$dbConnection;
  }

}
?>