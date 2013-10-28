<?php
/**
 * Request class file.
 * This file contains the definition for the Request class, which extends Slim_Http_Request to add specific
 * functionality to do with the SRS application.
 * @package classes
 * @filesource
 */
/**
 * Request class adding functionality to parse json packets sent by Backbone adding them to the appropriate put, get
 * or post array.  It also adds a route functionality concatenating 'action' and 'subject'
 * @package classes
 * @author  Rob Davis
 *          Date: 15/04/2013
 */

require_once 'classes/Slim_Http_Request.class.php';

class R_SlimRequest extends Slim_Http_Request {

    /**
     * overrides method in Slim_Http_Request
     */
    public function __construct() {
        parent::__construct();
        $this->loadJsonParameters();
    }

    /**
     * loads any json packets sent into their appropriate array, get | post | put
     */
    protected function loadJsonParameters() {
        $method = strtolower($this->method);
        if ($this->getContentType() === 'application/json') {
            $data = json_decode($this->body, true);
            if ($method === 'put') {
                $this->put = array_merge($this->put, $data);
            }
            elseif ($method === 'post') {
                $this->post = array_merge($this->put, $data);
            }
            elseif ($method === 'get') {
                $this->get = array_merge($this->get, $data);
            }
        }
    }

    /**
     * @return string concatenates the 'action' and the 'subject' into a route, the former being lowercase and the latter
     *          title case
     * @example action=list&subject=students gives route of listStudents
     */
    public function getRoute() {
        $action  = $this->params('action');
        $subject = $this->params('subject');
        if ((!empty($action)) && (!empty($subject))) {
            $route = strtolower($action) . ucfirst(strtolower($subject));
        }
        else {
            $route = 'default';
        }
        return $route;
    }


}