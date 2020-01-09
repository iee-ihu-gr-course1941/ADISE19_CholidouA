<?php
   define('DB_SERVER', '/home/student/it144329/mysql/run/mysql.sock');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '123');
   define('DB_DATABASE', 'triliza');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
?>