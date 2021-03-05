<?php

require_once ('db_credentials.php');

function db_connect(/*$user*/){
    //$DB_HOST = '';
    /*if($user == 'staff'){
        $DB_HOST = DB_ROOT;
    }
    else{
        $DB_HOST = DB_CUSTOMER;
    }*/
    $db = new mysqli(DB_SERVER,DB_USER,DB_PWD,DB_NAME);
    //$db->autocommit(FALSE);
    //$db->select_db("proj");
    if($db->connect_errno){
        prinf("Connect failed: %s\n", $db->connect_error);
        exit;
    }
    return $db;
}

function db_disconnect($mydb){
    if(isset($mydb)){
        $mydb->close();
    }
}

function confirm_db_connect($db) {
    if($db->connect_errno) {
        $msg = "Database connection failed: ";
        $msg .= $db->connect_error;
        $msg .= " (" . $db->connect_errno . ")";
        exit($msg);
    }
}

function db_escape($db,$string) {
    return $db->real_escape_string($string);
}

function confirm_result_set($result_set) {
    if (!$result_set) {
        exit("Database query failed.");
    }
}