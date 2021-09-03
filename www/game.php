<?php

// starting session for session variables
session_start();

//checking if user has logged in and if not then takes them to login/register page
if(!isset($_SESSION['username'])){
  header("Location: index.php"); /* Redirect browser */
  exit;
}
// if the row variable that decides the game isn't set, go back to select game page
if(!isset($_GET['row'])){
  header("Location: selectgame.php"); /* Redirect browser */
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <!-- use for different screen size -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- description -->
    <meta name="description" content="2018 MMOS Software Development SAT">

    <!-- author -->
    <meta name="author" content="Matthew Doyle">

    <!-- date last updated -->
    <meta name="date" content="28/8/2018">

    <!-- page icon -->
    <link rel="icon" href="images/icon.jpeg">

    <!-- page title -->
    <title>Soft Dev SAT</title>

    <!-- Main CSS -->
    <link rel="stylesheet" href='css/main.css'>

    <!-- opening php -->
    <?php

    // getting current row from last page
    $currentrow = $_GET['row'];

    // open games csv file
    $file = new SplFileObject("csv/games.csv", "r+");
    $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

    // set row
    $row = [];

    // set game array
    $game = [];

    // setting counting variables
    $i = 0;
    $l = 4;

    // reading to the end of the file
    while(!$file->eof()){
      // reading each row into row variable
      $row = $file->fgetcsv();
      // finding the gam wanted
      if ($i == $currentrow){
        // setting array for game details
        $user = $row;
        // creating 2d array for gameboard from csv file by counting through row then column
        for ($j=0; $j < 3; $j++) {
          for ($k=0; $k < 3; $k++) {
            $game[$j][$k] = $row[$l];
            $l++;
          }
        }
      }
      // if not game, put them all in a records array for reading in later
      else {
        $records[$i] = $row;
      }
      //counting up variable
      $i++;
      }

      // setting player 1, player 2, and turn number for game
      $player1 = $user[0];
      $player2 = $user[1];
      $turnno = $user[2];

      // setting token and opponent by user
      // if user is player 1
      if($_SESSION['username'] == $player1) {
        // their token is X and player 2 is their opponent
        $token = "X";
        $_SESSION['opponent'] = $player2;
      }
      // if user is player 2
      else {
        // their token is O and player 1 is their opponent
        $token = "O";
        $_SESSION['opponent'] = $player1;
      }

    // if confirm move button pressed
    if (isset($_POST['confirm'])) {
      // if move is selected
      if (isset($_POST['place'])) {
        // get place from input
        $place = $_POST['place'];

        // set row by function of place variable
        $row = intval($place/3);
        // set column but function of place variable
        $column = fmod($place, 3);

        // set the position selected to th token of the current player
        $game[$row][$column] = $token;

            // increase turn number by 1
            $user[2]++;

            // swapping whos turn it is by checking current player and setting game array to other player
            if($_SESSION['username'] == $player1) {
              $user[3] = $player2;
            }
            else{
              $user[3] = $player1;
            }

            // setting win and endgame variables
            $win = "false";
            $endgame = "false";

            // Algorithm for checking win. This algorithm uses for and if functions to automatically decide if it's 3 in a row. This checks rows, then columns then diaginals at the end. It is as efficent by using for loops to run through all the rows and columns and if statements reading as little lines as possible, only checking if the previous position is full set to the right token. It also only needs to check the current player as you can only win on your turn.
            // for loop to read through the rows
            for($i = 0; $i < 3; $i++) {
              // if statements to only progress if the last one is set
              if($game[$i][0] == $token){
                if($game[$i][1] == $token) {
                  if($game[$i][2] == $token) {
                      // if 3 in a row, setting win and endgame variables to true
                      $win = "true";
                      $endgame = "true";
                  }
                }
              }
            }
            // for loop to read through the columns
            for($i = 0; $i < 3; $i++) {
              // if statements to only progress if the last one is set
              if($game[0][$i] == $token){
                if($game[1][$i] == $token) {
                  if($game[2][$i] == $token) {
                    // if 3 in a row, setting win and endgame variables to true
                      $win = "true";
                      $endgame = "true";
                  }
                }
              }
            }
              // checking the first diagonal
              // if statements to only progress if the last one is set
              if($game[0][0] == $token){
                if($game[1][1] == $token) {
                  if($game[2][2] == $token) {
                    // if 3 in a row, setting win and endgame variables to true
                      $win = "true";
                      $endgame = "true";
                  }
                }
              }
              // checking the second diagonal
              // if statements to only progress if the last one is set
              if($game[2][0] == $token){
                if($game[1][1] == $token) {
                  if($game[0][2] == $token) {
                    // if 3 in a row, setting win and endgame variables to true
                      $win = "true";
                      $endgame = "true";
                  }
                }
              }
              // if the board has been filled with no wins, end game with no win
              if($user[2] == 9){
                $endgame = "true";
              }

            // closing file
            $file = null;

            // opening games csv file with w+ to truncate it and write it back into file
            $file = new SplFileObject("csv/games.csv", "w+");
            $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

            // setting counting variable back to 0
            $i = 0;

            // set line
            $line = [];

            // if endgame is true, get rid of last array as when left it counts too far and leaves an empty array
            if($endgame == "true"){
              array_pop($records);
            }

            // setting games file back into csv file in same places edited
            // run through get row in records array
            foreach ($records as $row) {
              // if game over, skip over the the line of game to move everything up so an empty line isnt left in the file once game is over
              if ($endgame == "true" && $i == $currentrow) {
                $i++;
              }
              // set line to records row
              $line = $records[$i];
              // if game isnt over, read game line into file
              if ($i == $currentrow && $endgame !== "true") {
                $line = null;
                // set game details into file
                for($j = 0; $j < 4; $j++){
                  $line[$j] = $user[$j];
                }
                // set game board into file
                for ($k=0; $k < 3; $k++) {
                  for ($l=0; $l < 3; $l++) {
                    $line[$j] = $game[$k][$l];
                    // count up
                    $j++;
                  }
                }
              }
              // is line is set
              if ($line[0] !== '') {
                // read line into file
                $file->fputcsv($line);
                // count up
                $i++;
              }
              // empty line
              $line = null;
            }

            // close file
            $file = null;

            // if game over
            if($endgame == "true") {
              // open stats csv file
              $file = new SplFileObject("csv/stats.csv", "r+");
              $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

              // set row
              $row = [];

              // set counting variable back to 0
              $i = 0;

              // reading to the end of the file
              while(!$file->eof()){
                // reading each row into row variable
                $row = $file->fgetcsv();
                // setting all stats into array
                $stats[$i] = $row;
                // counting up
                $i++;
                }

                // set counting avriable back to 0
                $i = 0;

                // algorithm for updating stats of both players after game
                // loop through stats to find both user and opponent
                foreach($stats as $row) {
                  // find user line
                  if ($stats[$i][0] == $_SESSION['username']) {
                    // update number of games
                    $stats[$i][1] = $stats[$i][1] + 1;
                    // if they won
                    if($win == "true") {
                      // plus 1 to wins
                      $stats[$i][2] = $stats[$i][2] + 1;
                    }
                    // if draw
                    else {
                      // plus 1 to draw
                      $stats[$i][3] = $stats[$i][3] + 1;
                    }
                    // work out score for 3 points for a win and 1`point for a draw
                    $stats[$i][5] = 3*$stats[$i][2] + $stats[$i][3];
                    // work out winrate by number of wins divided by number of games
                    $stats[$i][6] = $stats[$i][2]/$stats[$i][1];
                  }
                  // find opponent line
                  elseif($stats[$i][0] == $_SESSION['opponent']) {
                    // updaye number of games
                    $stats[$i][1] = $stats[$i][1] + 1;
                    // if user won
                    if($win == "true") {
                      // plus 1 to opponents loses
                      $stats[$i][4] = $stats[$i][4] + 1;
                    }
                    // if draw
                    else {
                      // plus 1 to draw
                      $stats[$i][3] = $stats[$i][3] + 1;
                    }
                    // work out score for 3 points for a win and 1`point for a draw
                    $stats[$i][5] = 3*$stats[$i][2] + $stats[$i][3];
                    // work out winrate by number of wins divided by number of games
                    $stats[$i][6] = $stats[$i][2]/$stats[$i][1];
                  }
                  // counting up
                  $i++;
                }

                // close file
                $file = null;

                // opening stats csv file with w+ to truncate it and write it back into file
                $file = new SplFileObject("csv/stats.csv", "w+");
                $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

                // set counting avribale back to 0
                $i = 0;

                // get rid of empty line
                array_pop($stats);

                // reading rows back into csv file
                foreach ($stats as $row) {
                  for($j = 0; $j < 7; $j++) {
                    // set line
                    $line[$j] = $stats[$i][$j];
                  }
                  // put line into file
                  $file->fputcsv($line);
                  // counting up
                  $i++;
                  // reset line
                  $line = null;
                }
              }

              // if game over
              if($endgame == "true") {
                // if user won
                if($win == "true"){
                  // set result for winscreen
                  $_SESSION['result'] = "win";
                }
                // set game type for win screen
                $_SESSION['gametype'] = "online";
                // being user to win screen
                header("Location: wingame.php"); /* Redirect browser */
                exit;
              }

            // empty opponentsession variable
            $_SESSION['opponent'] = null;
            // bring user to select game screen
            header("Location: selectgame.php"); /* Redirect browser */
            exit;
          }
        // if move not set
        else {
          // popup to tell alert user to select a move
          //opening js for alert
          ?>
          <script>
          // alert for popup
          alert("Enter Move");
          </script>
          <?php
        }
      }
      ?>
  </head>
  <body>
    <!-- open container -->
    <div class="container">
      <!-- header for title -->
        <header>
          <!-- page title -->
        <h2 class="gametitle">Tic Tac Toe</h2>
      </header>
            <!-- game div -->
            <div class="game">
              <!-- game details left div -->
              <div class="topleft">
                <!-- display player 1 and 2 -->
                <p>Player 1: <?php echo $player1; ?></p>
                <p>Player 2: <?php echo $player2; ?></p>
              </div>
              <!-- game details right div -->
              <div class="topright">
                <!-- display what your token is and the turn number -->
                <p>Your Token: <?php echo $token; ?></p>
                <p>Current Turn: <?php echo $turnno + 1; ?></p>
              </div>
              <!-- game board -->
              <table class="board">
                <!-- form for game inputs -->
                <form method="post">
                  <?php
                    // setting third counting variable to 0
                    $k = 0;
                    //lalorithm for automatically setting up the board table with the array and different values for input.
                    // for loop for columns
                    for ($i=0; $i < 3; $i++) { ?>
                    <!-- open row -->
                    <tr>
                            <!-- for loop for each place in the rows -->
                      <?php for ($j=0; $j < 3; $j++) { ?>
                      <!-- table box -->          <!-- checking if the position is set -->
                      <td class="gameplace"><?php if($game[$i][$j] == '') {
                                                    // if it's not set, set a radio button for selection with the token for the selection display. $k for each square to have a unique value for collecting it
                                                    echo "<input type='radio' class='option-input".$token." radio ' name='place' value='$k'>"; }
                                                  else {
                                                    // if it is set, output the token it's set to
                                                    echo $game[$i][$j];
                                                  } ?>
                                                    </td>
                                                    <!-- variable counts up -->
                                                  <?php $k++; } ?>
                                                <!-- once done with row, moves on to next row -->
                                                </tr>
                                                <?php } ?>
              </table>
            </div>
            <!-- confirm turn button -->
            <input class="confirm" type="submit" name="confirm" value="Confirm Move">
          </form>
    </div>
  </body>
</html>
