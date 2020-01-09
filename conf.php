<?php

$db = new mysqli('localhost', 'root', '123', 'triliza',null,'/home/student/it/2014/it144329/mysql/run/mysql.sock');
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . 
    $db->connect_errno . ") " . $db->connect_error;
}
?>