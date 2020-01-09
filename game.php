<?php
include('conf.php');
   include('session.php');
   $myid = $_SESSION['id'];
   
   //Display the user's name and score.
   $sql = "SELECT * FROM users WHERE id = '$myid'";
   $result = mysqli_query($db,$sql);
   $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
   
   echo 'Hello '.$row['username'].',<br>';
   echo 'your score is: '.$row['score'].' victories!<br>';
   echo '_______________________________________________<br>';

   
   //Helper-----------------------------------------------------------------------------------------
   function has_winner($b) {
      //Check all 8 ways to win and if someone won, return their number
      if (($b[0] == $b[1]) && ($b[0] == $b[2])) {
         return $b[0];
      }
      if (($b[0] == $b[3]) && ($b[0] == $b[6])) {
         return $b[0];
      }
      if (($b[0] == $b[4]) && ($b[0] == $b[8])) {
         return $b[0];
      }
      if (($b[1] == $b[4]) && ($b[1] == $b[7])) {
         return $b[1];
      }
      if (($b[2] == $b[4]) && ($b[2] == $b[6])) {
         return $b[2];
      }
      if (($b[2] == $b[5]) && ($b[2] == $b[8])) {
         return $b[2];
      }
      if (($b[3] == $b[4]) && ($b[3] == $b[5])) {
         return $b[3];
      }
      if (($b[6] == $b[7]) && ($b[6] == $b[8])) {
         return $b[6];
      }
	  
      //If there is not winner yet, check if the board is full
      $full = true;
      for ($i=0;$i<9;$i++) {
         if ($b[$i] == 0) {
            $full = false;
         break;
         }
      }

      //Game is a tie
      if ($full) {
         return 3;
      }

      //Game continues
      return 0;
   }
   
   //Helper-----------------------------------------------------------------------------------------

	  
    //Make player move and check if game is completed-----------------------------------------------------------------------------------------

   //If we have joined a game
   if (isset($_SESSION['current_game'])) {
      //Grab the game ID from Session
      $my_game = $_SESSION['current_game'];

      //If the user just sumbitted their next move
      if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['position']))) {
         
         //Grab the move number from POST
         $position = mysqli_real_escape_string($db,$_POST['position']);

         //Grab game data form the database
         $sql = "SELECT * FROM games WHERE id = '$my_game'";
         $result = mysqli_query($db,$sql);
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

         //Figure out if this player is p1 or p2
         $is_p2 = 0;
         if ($myid == $row['p2']) {
            $is_p2 = 1;
         }
         
         //If the game is not yet finished
         if ($row['completed'] == 0) {

            //If it was our turn to play, make the update
            if ($row['current'] == $is_p2) {
               $json_board = json_decode($row['jason']);
               //If it's a legal move
               if ($json_board[$position] == 0) {
                  //Mark the move on the board and re-encode it to JSON
                  $json_board[$position] = $is_p2 + 1;

                  //Check if game is finished
                  $new_completed = has_winner($json_board);

                  $json_board = json_encode($json_board);

                  //Update the board state, as well as the turn and completion indicators
                  $new_turn = 1-$is_p2;
                  $sql = "UPDATE games SET current = '$new_turn', jason = '$json_board', completed = '$new_completed'  WHERE id = '$my_game'";
                  $result = mysqli_query($db,$sql);

                  //Update score for the winner. If the game is now completed
                  if (($new_completed > 0) && ($new_completed < 3)) {
                     //Get the id of the winner
                     $winner = $row['p1'];
                     if ($new_completed == 2) { $winner = $row['p2'];}

                     //Get the old score of the winner and increase it by 1
                     $sql = "SELECT score FROM users WHERE id = '$winner'";
                     $result = mysqli_query($db,$sql);
                     $new_score = mysqli_fetch_array($result,MYSQLI_ASSOC)['score'] + 1;

                     //Update the score in the database
                     $sql = "UPDATE users SET score = '$new_score'WHERE id = '$winner'";
                     $result = mysqli_query($db,$sql);
                  }
               } 
            } else {
               echo "Please wait for your turn";
            }
         }
      }

   }
   
   //Make player move and check if game is completed-----------------------------------------------------------------------------------------
   
   //Find existing game, or join an open table, or create new table-----------------------------------------------------------------------------------------

      //If we haven't joined a game yet
      if (!isset($_SESSION['current_game'])) {
         $joined_game = false;

         //Case 1, try to find an open (not completed) game that we have joined in the past.
         $sql = "SELECT id FROM games WHERE (p1 = '$myid' OR p2 = '$myid') AND completed = 0 LIMIT 1";
         $result = mysqli_query($db,$sql);
         $count = mysqli_num_rows($result);

         if ($count == 1) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $_SESSION['current_game'] = $row['id'];
            $joined_game = true;
         }

         //Case 2, try to join an open game with an empty seat
         if (!$joined_game) {
            $sql = "SELECT id FROM games WHERE completed = 0 AND p2 = 0 ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($db,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $count = mysqli_num_rows($result);

            if ($count == 1) {
               echo "Found game with empty seat";
               $game_id = $row['id'];
                  
                  //Set p2 to our id
                  $sql = "UPDATE games SET p2 = '$myid' WHERE id = '$game_id'";
                  $result = mysqli_query($db,$sql);
                  $joined_game = true;
                  $_SESSION['current_game'] = $game_id;
            }
         }

         //Case 3, create a new game
         if (!$joined_game) {
            echo "Created game";
            $empty_board = array(0,0,0,0,0,0,0,0,0);
            $json_board = json_encode($empty_board);
            $sql = "INSERT INTO games (jason, p1) VALUES ('$json_board', '$myid')";
            $result = mysqli_query($db,$sql);

            $sql = "SELECT* FROM games WHERE p1 = '$myid' and completed = 0 ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($db,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            echo $row['id'];
            $_SESSION['current_game'] = $row['id'];
         }

      }
	  
	  //Find existing game, or join an open table, or create new tables-----------------------------------------------------------------------------------------
	  
	  //Show board and print winner if they exist-----------------------------------------------------------------------------------------

      //If we have joined a game, display the board. This should always run.
      if (isset($_SESSION['current_game'])) { 
         //Grab game from database using the game's id
         $my_game = $_SESSION['current_game'];
         $sql = "SELECT * FROM games WHERE id = '$my_game'";
         $result = mysqli_query($db,$sql);
         $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

         //See if we are p1 or p2
         $is_p2 = 0;
         if ($myid == $row['p2']) {
            $is_p2 = 1;
         }

         //display the game board
         echo '<br>I play as: '.($is_p2+1).'<br>';
         $json_board = json_decode($row['jason']);
         echo "Current player: ";
         echo $row['current']+1;
         echo "<br>";
         echo "<br>_______<br>";
         for ($i=0;$i<3;$i++) {
            echo $json_board[$i*3];
            echo " | ";
            echo $json_board[$i*3 + 1];
            echo " | ";
            echo $json_board[$i*3 + 2];
            echo "<br>_______<br>";
         }
         echo "<br><br>";
         $names = array("top-left", "top-center", "top-right", "middle-left", "middle-center", "middle-right", "bottom-left", "bottom-center", "bottom-right");

         //If the game is not completed
         if ($row['completed'] == 0) {
            //And it's OUR turn
            if ($row['current'] == $is_p2) {
               //display a dropdown menu with options for all available moves
               echo '<form action = "" method = "post"><select name="position">';
               for ($i=0;$i<9;$i++) {
                  if ($json_board[$i] == 0) {
                     echo '<option value="'.$i.'">'.$names[$i].'</option>';
                  }
               }

               echo '<input type = "submit" value = " Submit "/><br /></select></form>';
            } else { //Else just show a refresh button. The user needs to wait for the other player.
               echo '<form action = "" method = "post"><input type = "submit" value = " Refresh "/><br /></form>';
            }
         } else {//Else if game is over, display the winner.
            echo '<br>GAME OVER';
			
			if ($row['completed'] == 3) {
			echo '<br>it is a tie';
			}
			else {
            echo '<br>Player '.$row['completed'].' has won!';
			}
         }
      }
      
?>
<html">
   <head>
      <title>TicTacToe </title>
   </head>
   <body>
      <h2><a href = "logout.php">Log Out</a></h2>
   </body>
   
</html>