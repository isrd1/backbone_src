<?php
/**
 * A set of record set classes using PDO
 * @package ROBPDO
 */
require_once("pdoDB.class.php");
/**
 * abstract super that creates a database connection and returns a record set
 * @author Rob Davis
 *
 */
abstract class R_RecordSet {
    protected $db;
    protected $stmt;

    function __construct() {
        $this->db = pdoDB::getConnection();
    }

    function getRecordSet($sql, $params = null) {
        if (is_array($params)) {
            $this->stmt = $this->db->prepare($sql);
            // execute the statement passing in the named placeholder and the value it'll have
            $this->stmt->execute($params);
        }
        else {
            $this->stmt = $this->db->query($sql);
        }
        return $this->stmt;
    }
}

/**
 * Returns a plain record set for use with flex or a php client function that needs a normal record set
 * @author Rob Davis
 *
 */
class R_PDORecordSet extends R_RecordSet {
    /**
     * return a pdo record set
     * @param $sql    string containing the sql for the record set
     * @param $params is an array that if passed is used for prepared statements, it should be an assoc array of param name => value
     * @return recordset A PDO recordset
     */
    function getRecordSet($sql, $params = null) {
        return parent::getRecordSet($sql);
    }
}

/**
 * specialisation class that returns a record set as an xml string
 * @author Rob Davis
 */
class R_XMLRecordSet extends R_RecordSet {
    protected $xmlHeader = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";

    /**
     * function to return the record set as an xml string
     * @param          $sql          string with the sql to execute to retrieve the record set
     * @param          $elementName  string that will be the name of the repeating element
     * @param          $params       an array that if passed is used for prepared statements, it should be an assoc array of param name => value
     * @param          $insertHeader a string to allow for insertion of data before the repeating data of the record set
     * @param  bool    $noHeader     if true will prevent the xml doctype and the enclosing root element
     * @return string  $output       The record set as an xml tree
     */
    function getRecordSet($sql, $elementName = "element", $params = null, $insertHeader = null, $noHeader = false) {
        $stmt   = parent::getRecordSet($sql, $params);
        $output = $noHeader === true ? '' : $this->xmlHeader;
        $output .= $noHeader === true ? '' : "<{$elementName}s>\n";
        if (!is_null($insertHeader)) {
            $output .= $insertHeader;
        }
        while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $output .= "\t<$elementName>\n";
            foreach ($rs as $field => $value) {
                $value = htmlspecialchars($value);
                $output .= "\t\t<$field>$value</$field>\n";
            }
            $output .= "\t</$elementName>\n";
        }
        $output .= $noHeader === true ? '' : "</{$elementName}s>";
        return $output;
    }
}

/**
 * specialisation class that returns a record set as an json string
 * @author Rob Davis
 */
class JSONRecordSet extends R_RecordSet {
    /**
     * function to return a record set as a json encoded string
     * @param $sql         string with sql to execute to retrieve the record set
     * @param $elementName string that will be the name of the repeating elements
     * @param $params      is an array that if passed is used for prepared statements, it should be an assoc array of param name => value
     * @return bool|string false if no records or a json object showing the status, number of records and the records themselves
     */
    function getRecordSet($sql, $elementName = "ResultSet", $params = null) {
        $stmt     = parent::getRecordSet($sql, $params);
        $recordSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $nRecords = count($recordSet);
        if ($nRecords == 0) {
            return false;
        }
        else {
            return '{"status":"ok", "' . $elementName . '" :{"RowCount":' . $nRecords . ',"Result":' . json_encode($recordSet) . '}}';
        }
    }
}

?>