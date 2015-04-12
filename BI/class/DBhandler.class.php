<?php

define ('HOST', 'localhost');
define ('USER', 'root');
define ('PASSWORD', '199371helu');
define ('DB', 'dm_helinhai');

class DBhandler {
    private $_conn;
    
    private $_hostname;
    private $_username;
    private $_password;
    private $_database;
    
     public function __construct ( $hostname = HOST, $username = USER, $password = PASSWORD, $database = DB ) {
        $this->_hostname = $hostname;
        $this->_username = $username;
        $this->_password = $password;
        $this->_database = $database;
        $this->_init();
    }
    
    private function _init () {
        if ( $this->_create_connection() && $this->_select_db() ){
            //set UTF-8
            mysql_query("SET NAMES utf8", $this->_conn);
            return;
        }
        else{
            die("failed to initialize DB hander...program exit...");
        }
    }
    
    private function _create_connection () {
        $this->_conn = mysql_connect($this->_hostname,$this->_username, $this->_password);
        if ( !($this->_conn)) {
            return false;
        }
        else{
            return true; 
        }
    }
    
    private function _select_db () {
        $dbselect = mysql_select_db($this->_database, $this->_conn);
        if ( !$dbselect ) {
            return false;
        }
        else {
            return true;
        }
    }
    
    /**
     *执行insert，update语句 
     */
    public function execQuery ( $sql ) {
        if ( mysql_query($sql,$this->_conn) ) {
        }
        else {
            die("Error insert action: " . mysql_error());
        }
    }
    
    public function fetchQuery( $sql ) {
        $result = mysql_query($sql, $this->_conn);
        $result_array = mysql_fetch_row($result);
        if ( $result_array ) {
            return $result_array;
        }
        else {
            die ("Error fetch action: " . mysql_error());
        }
    }
    
    public function get_last_id () {
        return mysql_insert_id($this->_conn);
    }
    
   /* public function __destruct () {
        mysql_close($this->_conn);
    }*/
}

?>