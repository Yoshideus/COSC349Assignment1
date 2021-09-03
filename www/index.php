<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
  <head><title>Database test page</title>
    <style>
    th { text-align: left; }

    table, th, td {
      border: 2px solid grey;
      border-collapse: collapse;
    }

    th, td {
      padding: 0.2em;
    }

    body {
      position: relative;
    }

    .container {
      text-align: center;
    }
    </style>
  </head>

  <body>
    <h1>Database test page</h1>

    <p>Showing contents of papers table:</p>

    <table border="1">
      <tr><th>Paper code</th><th>Paper name</th></tr>

      <?php

      echo "<tr><td>TEST1</td><td>TEST2</td></tr>\n";

      $db_host   = '192.168.2.12';
      $db_name   = 'fvision';
      $db_user   = 'webuser';
      $db_passwd = 'insecure_db_pw';

      $pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

      $pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

      $q = $pdo->query("SELECT * FROM games");

      echo "<p>test3</p>";

      while($row = $q->fetch()){
        echo "<tr><td>".$row["gameid"]."</td><td>".$row["maxPlayers"]."</td></tr>\n";
      }

      ?>
    </table>

    <div class="container">
        <h1 class="title">Complete the Sentence Game</h1>
        <br>
        <h3>Lets play the Complete the Sentence Game!</h3>
        <br>
        <p class="rules">
           On each players turn they will be able to type
           one word that will then be added to the sentence.
           The aim of the game is to make the most fun sentence
           you can. When a player thinks a sentence is done,
           they can enter a . to complete it and the game will
           continue with another sentence.
         </p>
        <br>
        <button>Find Game!</button>
        <br>
        <label for="nextWord">Input the next word</label>
        <br>
        <input type="text" id="nextWord" name="nextWord">
    </div>
  </body>
</html>
