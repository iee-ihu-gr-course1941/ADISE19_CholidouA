<?php
   include("conf.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT id FROM users WHERE username = '$myusername' AND password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $count = mysqli_num_rows($result);

      if($count == 1) {
         //session_register("myusername");
         $_SESSION['username'] = $myusername;
         $_SESSION['id'] = $row['id'];
         
         header("location: game.php");
      }else {
         $error = "User not found or password is wrong";
      }
   }
?>
<html>
   
   <head>
      <title>TicTacToe Login</title>
   </head>
   
   <body>
	
      <div align = "center">
         <div style = "width:200px; border: solid 2px #9900ff; ">
            <div align = "center"; style = "background-color:#9900ff; color:#FFFFFF;"><b>Login</b></div>
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>Username  :</label><input type = "text" name = "username"/><br/><br/>
                  <label>Password  :</label><input type = "password" name = "password"/><br/><br/>
                  <input type = "submit" value = " Submit "/><br />
               </form>

               <?php if (isset($error)) {echo $error;} ?>
            </div>
         </div>
      </div>

   </body>
</html>