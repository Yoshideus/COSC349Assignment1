<?php

// starting session for session variables
session_start();

// canceling opponent and gametype session variables
$_SESSION['opponent'] = null;
$_SESSION['gametype'] = null;

//checking if user has logged in and if not then takes them to login/register page
if(!isset($_SESSION['username'])){
  header("Location: index.php"); /* Redirect browser */
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

    // open games csv file
    $file = new SplFileObject("csv/games.csv", "r+");
    $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

    // set row
    $row = [];

    // set counting variable
    $i = 0;

    // set games array
    $games = [];

    // set turn lists arrays
    $turn = [];
    $notturn = [];

    // algoithum to find all games the user in involved in, checking both player 1 and player 2, by running through the file and makes a 2 lists of the user's turn and the opponents turn.
    // reading to the end of the file
    while(!$file->eof()){
      // reading each row into row variable
      $row = $file->fgetcsv();
      // for loop through to check boith player 1 and player 2
      for ($j=0; $j < 2; $j++) {
        if($row[$j] == $_SESSION['username']) {
          // set into games
          $games[$i] = $row;
          // if it's not the user's turn
          if($row[3] !== $_SESSION['username']) {
            // put game into not turn list
            $notturn[$i] = $row;
            // set the number of row for collecting game from file
            $nottrun[$i][13] = $i;
          }
          // if it is the user's turn
          else {
            // put game into not turn list
            $turn[$i] = $row;
            // set the number of row for collecting game from file
            $turn[$i][13] = $i;
          }
        }
      }
      // counting up for each row
      $i++;
  }

      // if the user chooses a random opponent
      if(isset($_POST['random'])) {

        // opening accounts csv file
        $file = new SplFileObject("csv/accounts.csv", "r");
        $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

        // set row
        $row = [];

        // set counting variable
        $count = 0;

        // reading to the end of the file
        while(!$file->eof()){
          // reading each row into row variable
          $row = $file->fgetcsv();
          // set users as 2d array
          $users[] = $row[0];
          // counting number of rows
          $count++;
        }

        // setting variables
        $success = false;
        $ran = null;

        // looping until player is not the user
        while ($success == false){
          // fidnign random player in users
          $opp = $users[array_rand($users)];
          // getting out of loop if the opponent isnt the user
          if($opp !== $_SESSION['username'] && $opp !== '') {
            $success = true;
          }
        }

        // setting the oppoent session variable
        $_SESSION['opponent'] = $opp;

        // setting array for game
        $newgame = null;
        $newgame = [];
        $newgame[0] = $_SESSION['username'];
        $newgame[1] = $opp;
        $newgame[2] = 0;
        $newgame[3] = $_SESSION['username'];
        $newgame[4] = '';
        $newgame[5] = '';
        $newgame[6] = '';
        $newgame[7] = '';
        $newgame[8] = '';
        $newgame[9] = '';
        $newgame[10] = '';
        $newgame[11] = '';
        $newgame[12] = '';
        $newgame[13] = '';

        // closing file
        $file = null;

        // opening games csv file
        $file = new SplFileObject("csv/games.csv", "r+");
        $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

        // set counting variable
        $i = -1;

        // reading to the end of the file
        while(!$file->eof()){
          // reading through file
          $row = $file->fgetcsv();
          // counting how many lines in csv file
          $i++;
        }

        // putting new game into csv file
        $file->fputcsv($newgame);

        // closing file
        $file = null;

        // exit to the game with the row number to grab the game
        header("Location: game.php?row=$i"); /* Redirect browser */
        exit;
      }

      // when username find game button pressed
      if(isset($_POST['user'])){
        // getting username from input
        $opp = $_POST['username'];

        // setting error variable
        $error = "true";

        // opening accounts csv file
        $file = new SplFileObject("csv/accounts.csv", "r");
        $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

        // set row
        $row = [];

        // set counting variable
        $count = 0;

        // reading to the end of the file
        while(!$file->eof()){
          // reading each row into row variable
          $row = $file->fgetcsv();
          // search through accounts to find if the user exists, if so, error = false
          if($row[0] == $opp){
            $error = "false";
          }
        }

        //closing file
        $file = null;

          // open games csv file
          $file = new SplFileObject("csv/games.csv", "r");
          $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

          // if the username entered is the the same as the current user, set error true
          if($opp == $_SESSION['username']) {
            $error = "true";
          }

          // if successful
          if($error !== "true") {
            // set new game variables
            $newgame = null;
            $newgame = [];
            $newgame[0] = $_SESSION['username'];
            $newgame[1] = $opp;
            $newgame[2] = 0;
            $newgame[3] = $_SESSION['username'];
            $newgame[4] = '';
            $newgame[5] = '';
            $newgame[6] = '';
            $newgame[7] = '';
            $newgame[8] = '';
            $newgame[9] = '';
            $newgame[10] = '';
            $newgame[11] = '';
            $newgame[12] = '';

            // setting oppoent session avriable
            $_SESSION['opponent'] = $opp;

            // closing file
            $file = null;

            // opening games csv file
            $file = new SplFileObject("csv/games.csv", "r+");
            $file->setFlags(SplFileObject::READ_CSV|SplFileObject::SKIP_EMPTY|SplFileObject::READ_AHEAD);

            // set counting variable
            $j = -1;

            // reading to the end of the file
            while(!$file->eof()){
              // reading through file
              $row = $file->fgetcsv();
              // counting how many lines in csv file
              $j++;
            }

            // putting new game into csv file
            $file->fputcsv($newgame);

            // closing file
            $file = null;

            // exit to the game with the row number to grab the game
            header("Location: game.php?row=$j"); /* Redirect browser */
            exit;
          }
          else {
            // popup to tell alert user that their entry was incorrect
            //opening js for alert
            ?>
            <script>
            // alert for popup
            alert("That User does not exist or you have entered yourself");
            </script>
            <?php
          }
        }
          // if logout button is pressed
          if(isset($_POST['logout'])){
            //cancel username
            $_SESSION['username'] = null;
            // take user to login/register page
            header("Location: index.php"); /* Redirect browser */
            exit;
          }
        // if back button is selected
        if(isset($_GET['back'])){
          // bring user to gamemode
          header("Location: gamemode.php"); /* Redirect browser */
          exit;
        }
     ?>
  </head>
  <body>
    <!-- open container -->
    <div class="container">
      <!-- header for title -->
        <header>
          <!-- page title -->
        <h2>Select Game</h2>
        <!-- form for logout button -->
      <form method="post">
        <!-- logout button -->
        <input class="logout" type="submit" name="logout" value="Log out">
      </form>
      </header>
      <!-- break for spacing -->
      <br>
          <!-- left section -->
          <div class="statleft">
            <!-- small title -->
            <h4>CONTINUE GAME</h4>
              <!-- smaller title -->
              <p>YOUR TURN</p>
              <!-- blue background div -->
              <div class="blue">
                <!-- table for list of games where it's the user's turn -->
                <table class="select">
                  <?php
                  // checking which player in active game is the opponent
                  foreach ($turn as $row) {
                    // if player one is user, player 2 is opponent
                    if($row[0] ==  $_SESSION['username']) {
                      $opp = $row[1];
                    }
                    // else player 1 is opponent
                    else {
                      $opp = $row[0];
                    }
                    ?>
                    <tr>
                        <!-- first box is opponent -->
                        <td class="active"><?php echo $opp; ?></td>
                        <!-- then turn number thats about to happen -->
                        <td class="active"><?php echo $row[2] + 1; ?></td>
                        <!-- play link to play -->
                        <td class="active"><a href="game.php?row=<?php echo $row[13]; ?>">Play</a></td>
              <?php } ?>
                </table>
              </div>
              <!-- smaller title -->
              <p>OPPONENT'S TURN</p>
              <!-- blue background div -->
              <div class="blue">
                <!-- table for list of games where it's the opponent's turn -->
                  <table class="select">
                    <?php
                    // checking which player in active game is the opponent
                    foreach ($notturn as $row) {
                      // if player one is user, player 2 is opponent
                      if($row[0] ==  $_SESSION['username']) {
                        $opp = $row[1];
                      }
                      // else player 1 is opponent
                      else {
                        $opp = $row[0];
                      }
                      ?>
                        <tr>
                          <!-- first box is opponent -->
                          <td><?php echo $opp; ?></td>
                          <!-- then turn number thats about to happen -->
                          <td><?php echo $row[2] + 1; ?></td>
                <?php } ?>
                  </table>
                </div>
          </div>
          <!-- right div -->
          <div class="selectright">
            <!-- small title -->
            <h4>FIND A GAME</h4>
              <!-- smaller title -->
              <p>ENTER A USERNAME</p>
                <!-- form for posting username -->
                <form method="post">
                  <!-- blue background div -->
                  <div class="blue">
                    <!-- break for spacing -->
                    <br>
                    <!-- input for username, requried -->
                    <input class="findgame" type="text" name="username" value="" required>
                    <!-- break for spacing -->
                    <br>
                    <br>
                    <!-- button to submit username for game -->
                    <input class="findgame" type="submit" name="user" value="Find Game">
                    <!-- break for spacing -->
                    <br>
                    <br>
                  </div>
                </form>
                    <!-- smaller title -->
                    <p>RANDOM OPPONENT</p>
                    <!-- input for rnaodm game submition -->
                    <form method="post">
                    <!-- blue background div -->
                    <div class="blue">
                      <!-- break for spacing -->
                      <br>
                      <!-- button to submit for random game -->
                      <input class="findgame" type="submit" name="random" value="Find Game">
                      <!-- break for spacing -->
                      <br>
                      <br>
                    </div>
                </form>
              </div>
          </div>
          <!-- for for back button -->
          <form>
            <!-- back input -->
            <input class="selectback" type="submit" name="back" value="Back">
            <!-- break for speration -->
            <br>
          </form>
    </div>
  </body>
</html>
