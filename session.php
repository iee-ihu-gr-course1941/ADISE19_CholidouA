<?php
   include('conf.php');
   session_start();
   
   $user_exists = $_SESSION['username'];
   
   $ses_sql = mysqli_query($db,"SELECT username FROM users WHERE username = '$user_exists' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];
   
   if(!isset($_SESSION['username'])){
      header("location:login.php");
      die();
   }
?>