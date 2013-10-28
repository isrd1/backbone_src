<?php
/**
 * @filesource
 * @package classes
 */
/**
 * Registry class to handle reading and storing system info
 * @package classes
 * @author Rob Davis
 */
Abstract Class Registry {
	private function __construct() {}
    abstract protected function get($key);
    abstract protected function set($key, $value);
}

Class Session extends Registry {
    private static $instance;

    private function __construct() {
        session_start();
    }

    protected function get($key) {
        return self::getInstance()->$key;
    }
    protected function set($key, $value) {
        self::getInstance()->$key = $value;
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return null;
        }
    }

    public function __set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function removeKey($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        } else {
            return false;
        }
    }
}