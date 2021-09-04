<?php

// starting session for session variables
session_start();
// Set the username to null, completly login anyone out if they return to the index page
$_SESSION['username'] = null;

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <!-- use for different screen size -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- description -->
    <meta name="description" content="2020 COSC349 Assignment 1">

    <!-- author -->
    <meta name="author" content="Matthew Doyle">

    <!-- date last updated -->
    <meta name="date" content="03/9/2021">

    <!-- page icon -->
    <link rel="icon" href="images/icon.jpeg">

    <!-- page title -->
    <title>Soft Dev SAT</title>

    <!-- Main CSS -->
    <link rel="stylesheet" href='css/main.css'>

    <!-- opening php -->
    <?php

    $db_host   = '192.168.2.12';
    $db_name   = 'fvision';
    $db_user   = 'webuser';
    $db_passwd = 'insecure_db_pw';

    $pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

    $pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

    // setting row as empty for failed logins
    $row = null;

    // if the login button pressed
      if(isset($_POST['login'])){
        // grab username and password
        $username = $_POST['username'];
        $password = $_POST['password'];

        // setting success to false for username and password checking
        $success = false;

        $q = $pdo->query("SELECT * FROM users");

        while($row = $q->fetch()){
          if (strcmp($row["username"], $username) == 0) {
            // if user exists, check password
            if (password_verify($password, $row["password"]) == true) {
              // if password is correct login success
              $success = true;
              // get out if found
              break;
            }
          }
        }

        // check if login true
        if ($success == true) {
            // Set session username variable
            $_SESSION['username'] = $username;

            // change to menu
            header("Location: menu.php"); /* Redirect browser */
            // exit page
            exit();
        }
        // if login doesn't succeed
        else {
            // popup to tell user they failed
            //opening js for alert
            ?>
            <script>
            // alert for popup
            alert("The username and/or password you entered is incorrect");
            </script>
            <?php
        }
     }
     // if register button clicked
     elseif(isset($_POST['register'])){

        // set error true to for username and password checking
        $error = "true";

        // grab username and 2 passwords
        $Rusername = $_POST['Rusername'];
        $Rpassword1 = $_POST['Rpassword1'];
        $Rpassword2 = $_POST['Rpassword2'];

        // checking if the passwords enterd are the same
        if(strcmp($Rpassword1, $Rpassword2) == 0) {
          //if so, error is false
          $error = "false";
        }

        //hashing password with default hash for security
        $Rpassword1 = password_hash($Rpassword1, PASSWORD_DEFAULT);

        $q = $pdo->query("SELECT * FROM users");

        while($row = $q->fetch()){
          // puts row of csv in row
          $row = $file->fgetcsv();
          // checks if this the user exists in csv
          if ($row["username"] == $Rusername) {
            // if so, error
             $error = "true";
             // get out if found
             break;
            }
        }

        // if error is false
        if ($error == "false") {

          try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $pdo->exec("INSERT INTO users (username, password) VALUES ($Rusername, Rpassword1)");

            $pdo->exec("INSERT INTO stats (username, gamesplayed,wins,draws,loses,score, winrate) VALUES ($Rusername, 0, 0, 0, 0, 0, 0)");
          } catch(PDOException $e) {
            ?>
            <script>
            // alert for popup
            alert("Sorry, something went wrong, please try again.");
            </script>
            <?php
          }

            // setting session username variable
            $_SESSION['username'] = $Rusername;

            // change to menu
            header("Location: menu.php"); /* Redirect browser */
            // exit page
            exit;
        }
        else {
          // popup to tell user they failed
          //opening js for alert
          ?>
          <script>
            // alert for popup
            alert("Your username was taken or your passwords were not the same");
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
      <header class="hometitle">
        <!-- title -->
        <h1>Simple Games</h1>
      </header>
      <!-- login section -->
      <div class="left">
        <!-- section title -->
        <h3>Log in</h3>

          <form method="post">
            <!-- large text -->
            <h6>Enter details</h6>
              <!-- input titles -->
              <p>Username</p>
              <!-- input -->
              <input class="homeinput" type="text" name="username" value="" required>
              <!-- input titles -->
              <p>Password</p>
              <!-- input -->
              <input class="homeinput" type="password" name="password" value="" required>
              <br></br>
              <!-- login button -->
              <input class="login homeinput" type="submit" name="login" value="Login">
          </form>
      </div>
      <!-- register section -->
      <div class="right">
        <!-- section title -->
        <h3>Register</h3>
          <!-- form for ogin inputs -->
          <form method="post">
            <!-- large text -->
            <h6>Enter details</h6>
              <!-- input titles -->
              <p>Username</p>
              <!-- inputs -->
              <input class="homeinput" type="text" name="Rusername" value="" required>
              <!-- input titles -->
              <p>Password</p>
              <!-- input -->
              <input class="homeinput" type="password" name="Rpassword1" value="" required>
              <!-- input titles -->
              <p>Confirm Password</p>
              <!-- input -->
              <input class="homeinput" type="password" name="Rpassword2" value="" required>
              <br></br>
              <!-- register button -->
              <input class="register homeinput" type="submit" name="register" value="Register">
          </form>
      </div>
    </div>
    </body>
    </html>
