<?php

/**
 * System Registry information
 * @filesource
 * @package classes
 * @author  Rob Davis
 */
/**
 * require the base registry class and the pdoDB class
 */
require_once('registry.class.php');
require_once('pdoDB.class.php');

/**
 *
 * Class to handle Application level settings.
 * The class implements the singleton pattern, though the instance is private and held internally.
 * The only public interface to this class is via public static methods that expose system level values, used like
 * ApplicationRegistry::getDNS() to return the dns string for database connections.
 * @author  Rob Davis
 * @package classes
 * @example ../examples/StudentList-class.php Showing use of application registry
 *
 */
Class ApplicationRegistry extends Registry {

    /**
     * Singleton instance.
     * Holds a private reference to 'self' to ensure that only one copy of the ApplicationRegistry at any one time.
     * @var ApplicationRegistry
     */
    private static $instance;
    /**
     * Private array holding application values
     * @var Array $values
     */
    private $values = array();
    /**
     * Holds a flag showing whether the values array has altered
     * @var boolean
     */
    private $dirty = false;

    /**
     * Opens system config file.
     * Opens by calling the private function {@link ApplicationRegistry::openSystemConfigFile()}
     */
    private function __construct() {
        $this->openSystemConfigFile();
    }

    /**
     * Reads system file, used by constructor which is a singleton so only read once.
     * A function that opens the system config file and reads the values into
     * the values array
     * {@source}
     * @see     ApplicationRegistry::__construct()
     * @example ../examples/config.example.xml example xml config file - should be kept above web root
     */
    private function openSystemConfigFile() {
        $filename   = CONFIGLOCATION;
        $freezefile = $filename . '.freeze';
        // if a freezefile exists (a serialized version of $this->values created in $this->save)
        if (file_exists($freezefile)) {
            $this->values = unserialize(file_get_contents($freezefile));
        }
        else {
            if (file_exists($filename)) {
                // if the last four characters aren't .php
                if (stripos($filename, '.php', strlen($filename) - strlen('.php')) === false) {
                    $temp = simplexml_load_file($filename);
                }
                else {
                    // it's an xml file but wrapped in php tags and the xml in a comment
                    // to 'hide' the xml if the file is just loaded
                    // since it's inside the web root
                    $temp = $this->getXMLfromPHPFile($filename);
                }
                foreach ($temp as $key => $value) {
                    $this->set($key, trim($value));
                }
            }
        }
    }

    /**
     * Given a php filename it will extract xml embedded in it as a comment.
     * It is better to use an xml file above the web root, however, if this is not possible for some reason you can give the xml file
     * a php extension and include the xml as a comment in that php file.  This function will find that xml and parse that.
     * @example ../examples/config.xml.php Example xml file 'inside' php comments
     * @param string $xmlphp
     * @return string
     */
    private function getXMLfromPHPFile($xmlphp) {
        $temp         = file_get_contents($xmlphp);
        $firstLT      = stripos($temp, '<', 2);
        $lastGT       = strrpos($temp, '*/');
        $configString = trim(substr($temp, $firstLT, $lastGT - ($firstLT + 1)));
        $temp         = simplexml_load_string($configString);
        return $temp;
    }

    /**
     * Set a value for a key
     * Called only by the constructor calling {@link ApplicationRegistry::openSystemConfigFile()}
     * @param string $key
     * @param mixed  $value
     */
    protected function set($key, $value) {
        $this->dirty        = true;
        $this->values[$key] = $value;
    }

    /**
     * returns the dns used by the application.
     * This is one of several static functions in ApplicationRegistry to provide access to oft used values.  This returns the dns connection string for the database
     * @return string Database DNS string
     */
    public static function getDNS() {
        return self::instance()->get('dns');
    }

    /**
     * Get the database username
     * @return string database username
     */
    public static function getUsername() {
        return self::instance()->get('username');
    }

    /**
     * Returns the value for a previously set key
     * Accessed only from public static functions within this class {@link ApplicationRegistry::getDNS()}
     * @param mixed $key
     * @return mixed
     */
    protected function get($key) {
        return isset($this->values[$key]) ? $this->values[$key] : null;
    }

    /**
     * Singleton instance.
     * Method to create a new instance if one doesn't exist or return the one created if it does already exist.
     * @return ApplicationRegistry object self
     */
    private static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the database password
     * @return string database password
     */
    public static function getPassword() {
        return self::instance()->get('password');
    }

    /**
     * Returns the root application path
     * @return string root path
     */
    public static function getRoot() {
        return self::instance()->get('rootPath');
    }

    /**
     * Gives a database connection.
     * Static function to return a database connection to hide the pdoDB implementation
     * @return pdoDB database connection
     * @see pdoDB::getConnection()
     */
    public static function DB() {
        return pdoDB::getConnection();
    }

    /**
     * Saves the serialized value array
     * Writes the serialized value array to the config.freeze file if if any
     * config settings have been altered.  Called when the object is destroyed
     * {@source}
     */
    public function __destruct() {
        if ($this->dirty === true) {
            $temp = serialize($this->values);
            try {
                $h    = @fopen(CONFIGLOCATION . '.freeze', 'wb');
                if ($h !== false) {
                    fwrite($h, $temp);
                    fclose($h);
                }
            } catch (Exception $e) {
                // couldn't write file but just keep quiet, probably because the config directory isn't writeable
            }
            $this->dirty = false;
        }
    }

}