<?php
/**
 * Sets the environment
 * Could contain time and region settings, at the moment it just contains the location of the config.xml file and error level and display.
 * @filesource
 * @package resources
 */
/**
 * Set up environment change this to the path for your config.xml.php file which contains the db connection information
 */
define('CONFIGLOCATION', '/pathToApplication/server/config/config.xml.php');
// turn on all possible errors
error_reporting(-1);
// display errors, should be value of 0, in a production system of course
ini_set("display_errors", 1);
date_default_timezone_set('Europe/London');
// set the absolute path to the server directory
$path = '/pathToApplication/server';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
?>